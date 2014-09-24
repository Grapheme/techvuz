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

    ## Relations many-to-many: DicVal-to-DicVal
    public function relations() {
        return $this->belongsToMany('DicVal', 'dictionary_values_rel', 'dicval_parent_id', 'dicval_child_id');
    }

    public function allfields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id');
    }

    public function fields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id')->where('language', Config::get('app.locale'))->orWhere('language', NULL);
    }


    /**
     * Функция принимает в качестве аргументов ID словаря и массив с условиями для выборки из таблицы значений словарей.
     * Условия представляют собой одномерный массив, у которого:
     * - ключи: соответствуют столбцу key таблицы dictionary_fields_values
     * - значения: соответствуют столбцу value таблицы dictionary_fields_values
     * Функция делает выборку из БД, подсчитывая кол-во подходящих записей "значений словарей" под заданные условия.
     *
     * @param integer $dic_id
     * @param array $array
     * @return $this|DicFieldVal
     *
     * @author Alexander Zelensky
     */
    public static function count_by_fields($dic_id, $array) {
        #SELECT *  FROM `dictionary_fields_values` WHERE `dicval_id` = 162 AND `key` = 'collection_id' AND `value` = 161
        $tbl_dicval = new DicVal;
        $tbl_dicval = $tbl_dicval->getTable();
        $result = new DicFieldVal;
        $tbl_dicfieldval = $result->getTable();
        foreach ($array as $key => $value) {
            $result = $result->where('key', $key)->where('value', $value);
        }
        $result = $result
            ->join($tbl_dicval, $tbl_dicval.'.id', '=', $tbl_dicfieldval.'.dicval_id')
            ->where($tbl_dicval.'.dic_id', $dic_id)
        ;
        $result = $result->select($tbl_dicfieldval.'.*')->count();
        #Helper::ta($result);
        return $result;
    }

    /**
     * Функция принимает в качестве аргументов массив с ID словарей и массив с условиями для выборки из таблицы значений словарей.
     * Условия представляют собой одномерный массив, у которого:
     * - ключи: соответствуют столбцу key таблицы dictionary_fields_values
     * - значения: соответствуют столбцу value таблицы dictionary_fields_values
     * Функция делает выборку из БД, подсчитывая кол-во подходящих записей "значений словарей" под заданные условия для каждого словаря.
     *
     * @param array $dic_ids
     * @param array $array
     * @return $this|DicFieldVal
     *
     * @author Alexander Zelensky
     */
    public static function counts_by_fields($dic_ids = array(), $array = array()) {
        $tbl_dicval = new DicVal;
        $tbl_dicval = $tbl_dicval->getTable();
        $result = new DicFieldVal;
        $tbl_dicfieldval = $result->getTable();
        foreach ($array as $key => $value) {
            #Helper::dd($value);
            if (is_array($value))
                $result = $result->where($tbl_dicfieldval.'.key', $key)->whereIn($tbl_dicfieldval.'.value', $value);
            else
                $result = $result->where($tbl_dicfieldval.'.key', $key)->where($tbl_dicfieldval.'.value', $value);
        }
        $result = $result
            ->join($tbl_dicval, $tbl_dicval.'.id', '=', $tbl_dicfieldval.'.dicval_id')
            ->whereIn($tbl_dicval.'.dic_id', $dic_ids)
        ;

        ## Делаем выборку всех подходящих записей...
        $result = $result->select($tbl_dicfieldval.'.*', $tbl_dicval.'.dic_id')->get();

        ## DEBUG
        $queries = DB::getQueryLog();
        #Helper::smartQuery(end($queries), 1);
        #Helper::ta($result);
        #Helper::smartQueries(1);

        ## Собираем числа в массив и группируем по dicval_id -> dic_id
        $counts = array();
        foreach ($result as $r => $record) {

            if (!@is_array($counts[$record->value]))
                $counts[$record->value] = array();

            if (!@is_array($counts[$record->value][$record->dic_id]))
                $counts[$record->value][$record->dic_id] = array();

            #@++$counts[$record->dicval_id][$record->dic_id];
            $counts[$record->value][$record->dic_id][$record->dicval_id] = 1;
        }
        foreach ($counts as $dicval_id => $data) {
            foreach ($data as $dic_id => $elements) {
                $counts[$dicval_id][$dic_id] = count($elements);
            }
        }
        #Helper::dd($counts);

        return $counts;
    }

    public static function extracts($elements, $unset = false) {
        foreach ($elements as $e => $element) {
            $elements[$e] = $element->extract($unset);
        }
        return $elements;
    }


    public function extract($unset = false) {

        #Helper::ta($this);

        ## Extract allfields (without language & all i18n)
        if (isset($this->allfields) && @is_object($this->allfields) && count($this->allfields)) {

            foreach ($this->allfields as $field) {
                $this->{$field->key} = $field->value;
            }
            if ($unset)
                unset($this->allfields);

        } elseif (isset($this->fields) && @is_object($this->fields) && count($this->fields)) {

            ## Extract fields (with NULL language or language = default locale)
            foreach ($this->fields as $field) {
                $this->{$field->key} = $field->value;
            }
            if ($unset)
                unset($this->fields);

        }

        #Helper::ta($this);

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

        #Helper::d($dic_slug);
        #Helper::d($array);

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