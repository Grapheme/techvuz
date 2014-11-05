<?php

class DicVal extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary_values';
    #public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'version_of',
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
        return $this->belongsTo('Dictionary', 'dic_id');
    }

    public function metas() {
        return $this->hasMany('DicValMeta', 'dicval_id', 'id');
    }

    public function meta() {
        return $this->hasOne('DicValMeta', 'dicval_id', 'id')->where('language', Config::get('app.locale'));
    }

    /**
     * Связь многие-ко-многим между элементами DicVal, с привязкой к dic_id
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_dicvals() {
        return $this->belongsToMany('DicVal', 'dictionary_values_rel', 'dicval_parent_id', 'dicval_child_id');
    }

    /**
     * relations - алиас для свзяи, желательно использовать related_dicvals()
     */
    public function relations() {
        return $this->related_dicvals();
    }


    public function allfields() {
        return $this->hasMany('DicFieldVal', 'dicval_id', 'id');
    }

    public function fields() {
        return $this
            ->hasMany('DicFieldVal', 'dicval_id', 'id')->where('language', Config::get('app.locale'))->orWhere('language', NULL)
            ;
    }

    public function versions() {
        return $this->hasMany('DicVal', 'version_of', 'id')->orderBy('updated_at', 'DESC');
    }

    public function original_version() {
        return $this->hasOne('DicVal', 'id', 'version_of');
    }

    /**
     * Need to TEST
     */
    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'dicval')
            ->where('language', Config::get('app.locale'))
            #->where('language', NULL)
            ;
    }
    public function seos() {
        return $this->hasMany('Seo', 'unit_id', 'id')->where('module', 'dicval');
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
        #Helper::d($array);
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
            ->where($tbl_dicval.'.version_of', NULL)
        ;

        ## Делаем выборку всех подходящих записей...
        $result = $result->select($tbl_dicfieldval.'.*', $tbl_dicval.'.dic_id')->get();

        ## DEBUG
        $queries = DB::getQueryLog();
        #Helper::smartQuery(end($queries), 1); die;
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

    /*
    public static function get_relations(array $dicval_ids, $field) {
        if (
            !isset($dicval_ids) || !is_array($dicval_ids) || !count($dicval_ids)
            || !isset($field) || !$field
        )
            return array();

        Helper::d($dicval_ids);
        ##................
    }
    */

    public function scopeFilter_by_field($query, $key, $condition_or_value, $value = NULL) {

        #return $query->where('votes', '>', 100);

        if ($value === NULL) {
            $value = $condition_or_value;
            $condition = '=';
        } else {
            $condition = $condition_or_value;
        }

        $tbl_dicval = (new DicVal())->getTable();
        $tbl_dic_field_val = (new DicFieldVal())->getTable();
        $rand_tbl_alias = md5(time() . rand(999999, 9999999));
        $query->join($tbl_dic_field_val . ' AS ' . $rand_tbl_alias, $rand_tbl_alias . '.dicval_id', '=', $tbl_dicval . '.id')
            ->where($rand_tbl_alias . '.key', '=', $key)
            ->where($rand_tbl_alias . '.value', $condition, $value);

        return $query;
    }


    public static function extracts($elements, $unset = false, $extract_ids = true) {
        $return = new Collection;
        #Helper::dd($return);
        foreach ($elements as $e => $element) {
            $return[($extract_ids ? $element->id : $e)] = $element->extract($unset);
        }
        return $return;
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

        ## Extract SEOs
        if (isset($this->seos)) {
            #Helper::tad($this->seos);
            if (count($this->seos) == 1 && count(Config::get('app.locales')) == 1) {
                $app_locales = Config::get('app.locales');
                foreach ($app_locales as $locale_sign => $locale_name)
                    break;
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$locale_sign] = $seo;
                    break;
                }
                unset($this->seos[0]);
                #Helper::tad($this->seos);
            } else {
                foreach ($this->seos as $s => $seo) {
                    $this->seos[$seo->language] = $seo;
                    #Helper::d($s . " != " . $seo->language);
                    if ($s != $seo->language || $s === 0)
                        unset($this->seos[$s]);
                }
            }
        }

        ## Extract metas
        if (isset($this->metas)) {
            foreach ($this->metas as $m => $meta) {
                $this->metas[$meta->language] = $meta;
                if ($m != $meta->language || $m === 0)
                    unset($this->metas[$m]);
            }
        }

        ## Extract meta
        if (isset($this->meta)) {

            if (
                is_object($this->meta)
                && ($this->meta->language == Config::get('app.locale') || $this->meta->language == NULL)
            ) {
                if ($this->meta->name != '')
                    $this->name = $this->meta->name;

            }

            if ($unset)
                unset($this->meta);
        }

        #Helper::ta($this);

        ## Extract versions
        if (isset($this->versions)) {
            foreach ($this->versions as $v => $version) {
                $this->versions[$version->id] = $version;
                if ($v != $version->id || (int)$v === 0)
                    unset($this->versions[$v]);
            }
        }

        return $this;
    }

    public static function extracts_related($elements, $dicval_data = false, $extract_ids = true) {
        $return = new Collection;
        #Helper::dd($return);
        foreach ($elements as $e => $element) {
            $return[($extract_ids ? $element->id : $e)] = $element->extract_related($dicval_data, $extract_ids);
        }
        return $return;
    }

    public function extract_related($dicval_data = false, $extract_ids = true) {

        ## Extract relations
        if (isset($this->related_dicvals) && count($this->related_dicvals)) {
            $array = array();
            #Helper::tad($this->related_dicvals);
            foreach ($this->related_dicvals as $r => $relation) {

                $key = @$dicval_data[$relation->dic_id] ?: $relation->dic_id;
                if (!isset($array[$key]) ||!is_array($array[$key]))
                    $array[$key] = array();

                if ($extract_ids)
                    $array[$key][$relation->id] = $relation;
                else
                    $array[$key][] = $relation;
            }
            #Helper::tad($array);
            unset($this->related_dicvals);
            $this->related_dicvals = $array;
        }
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