<?php

class DicVal extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_values';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'dic_id',
        'slug',
        'name',
        'order',
    );

	public static $rules = array(
		'name' => 'required',
	);

    #public static function rules() {
    #    return self::$rules;
    #}

    public function dic() {
        return $this->belongsTo('Dictionary', 'dic_id')->orderBy('name');
    }

    public function metas() {
        return $this->hasMany('DicValMeta', 'dicval_id', 'id');
    }

    public function meta() {
        return $this->hasOne('DicValMeta', 'dicval_id', 'id')->where('language', Config::get('app.locale'));
    }

    public function allfields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id');
    }

    public function fields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id')->where('language', Config::get('app.locale'))->orWhere('language', NULL);
    }

    public function extract($unset = false) {
        ## Extract fields
        if (@is_object($this->allfields) && count($this->allfields)) {
            foreach ($this->allfields as $field) {
                $this->{$field->key} = $field->value;
            }
            if ($unset)
                unset($this->allfields);
        }

        ## Extract fields_i18n
        ## ...

        ## Extract metas
        ## ...

        ## Extract meta
        ## ...

        return $this;
    }

    /*
     * USAGE:
     *
       $dicval = DicVal::inject('transactions', array(
            'slug' => NULL,
            'name' => $nickname,
            'fields' => array(
                'quest_id' => $quest_id,
                'payment_amount' => $amount,
                'payment_date' => date("Y-m-d H:i:s"),
                'payment_method' => 'dengionline',
                'payment_full' => json_encode(array('paymode' => $mode_type)),
            ),
            'fields_i18n' => array(
                'ru' => array(
                    'quest_id' => $quest_id,
                    'payment_amount' => $amount,
                    'payment_date' => date("Y-m-d H:i:s"),
                    'payment_method' => 'dengionline',
                    'payment_full' => json_encode(array('paymode' => $mode_type)),
                ),
            ),
            'meta' => array(
                'en' => array(
                    'name' => 'ololo',
                ),
            ),
        ));
     */
    public static function inject($dic_slug, $array) {

        Helper::d($dic_slug);
        Helper::d($array);

        ## Find DIC
        $dic = Dic::where('slug', $dic_slug)->first();
        if (!is_object($dic))
            return false;

        ## Create DICVAL
        $dicval = new DicVal;
        $dicval->dic_id = $dic->id;
        $dicval->slug = @$array['slug'] ?: NULL;
        $dicval->name = @$array['name'] ?: NULL;
        $dicval->save();

        ## CREATE FIELDS
        if (@isset($array['fields']) && is_array($array['fields']) && count($array['fields'])) {
            $fields = array();
            foreach ($array['fields'] as $key => $value) {
                $dicval_field = new DicFieldVal();
                $dicval_field->dicval_id = $dicval->id;
                $dicval_field->language = is_array($value) && isset($value['language']) ? @$value['language'] : NULL;
                $dicval_field->key = $key;
                $dicval_field->value = is_array($value) ? @$value['value'] : $value;
                $dicval_field->save();

                $fields[] = $dicval_field;
            }
            #$dicval->fields = $fields;
        }

        ## CREATE FIELDS_I18N
        if (@isset($array['fields_i18n']) && is_array($array['fields_i18n']) && count($array['fields_i18n'])) {
            $fields_i18n = array();
            foreach ($array['fields_i18n'] as $locale_sign => $fields) {

                if (!@is_array($fields) || !@count($fields))
                    continue;

                $temp = array();
                foreach ($fields as $key => $value) {

                    $dicval_field_i18n = new DicFieldVal();
                    $dicval_field_i18n->dicval_id = $dicval->id;
                    $dicval_field_i18n->language = $locale_sign;
                    $dicval_field_i18n->key = $key;
                    $dicval_field_i18n->value = is_array($value) ? @$value['value'] : $value;
                    $dicval_field_i18n->save();

                    $temp[] = $dicval_field_i18n;
                }
                $fields_i18n[$locale_sign] = $temp;
            }
            #$dicval->fields_i18n = $fields_i18n;
        }

        ## CREATE META
        if (@isset($array['meta']) && is_array($array['meta']) && count($array['meta'])) {
            $metas = array();
            foreach ($array['meta'] as $locale_sign => $fields) {

                if (!@is_array($fields) || !@count($fields))
                    continue;

                $temp = array();
                foreach ($fields as $key => $value) {

                    $dicval_meta = new DicValMeta();
                    $dicval_meta->dicval_id = $dicval->id;
                    $dicval_meta->language = $locale_sign;

                    $dicval_meta->name = is_array($value) ? @$value['name'] : $value;
                    $dicval_meta->save();

                    $temp[] = $dicval_meta;
                }
                $metas[$locale_sign] = $temp;
            }
            #$dicval->metas = $metas;
        }

        ## RETURN EXTRACTED DICVAL
        return $dicval;
    }

}