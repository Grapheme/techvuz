<?php

class Dic extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary';
    #public $timestamps = false;

	public static $order_by = "name ASC";
    public static $cache_key = 'app.dics';

    protected $fillable = array(
        'slug',
        'name',
        'entity',
        'icon_class',
        'hide_slug',
        'make_slug_from_name',
        'name_title',
        'pagination',
        'view_access',
        'sort_by',
        'sort_order_reverse',
        'sortable',
        'order',
    );

	public static $rules = array(
		'name' => 'required',
	);

    #public $all_dicval_relations = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];


    /**
     * Связь возвращает все записи словаря со всеми полями; с сортировкой.
     *
     * @return mixed
     */
    public function values() {

        $tbl_dicval = (new DicVal())->getTable();

        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->select($tbl_dicval.'.*')
            ->where('version_of', NULL)
            ->with(['meta', 'fields', 'textfields', 'seo', 'related_dicvals'])
            ->orderBy(DB::raw('-lft'), 'DESC')
            ->orderBy('slug', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('id', 'ASC')
        ;
    }

    /**
     * Связь возвращает все записи словаря со всеми полями, без доп. условия сортировки.
     * Только для внутреннего использования.
     *
     * @return mixed
     */
    public function values_no_conditions() {

        $tbl_dicval = (new DicVal())->getTable();

        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->select($tbl_dicval.'.*')
            ->where('version_of', NULL)
            ;
    }

    /**
     * Связь возвращает ОДНУ запись из словаря. Только для использования с доп. условиями.
     *
     * @return mixed
     */
    public function value() {
        return $this->hasOne('DicVal', 'dic_id', 'id')->where('version_of', NULL);
    }

    /**
     * Возвращает количество записей в словаре
     *
     * @return int
     */
    public function values_count() {
        return DicVal::where('dic_id', $this->id)->where('version_of', NULL)->count();
    }



    /**
     * В функцию передается коллекция объектов, полученная из Eloguent методом ->get(),
     * а также название поля, значение которого будет установлено в качестве ключа для каждого элемента коллекции.
     *
     * @param object $collection - Eloquent Collection
     * @param string $key
     * @return object
     *
     * @author Alexander Zelensky
     */
    public static function modifyKeys($collection, $key = 'slug') {

        #Helper::tad($collection);
        $array = new Collection;

        foreach ($collection as $c => $col) {
            $current_key = is_object($col) ? $col->$key : @$col[$key];
            if (NULL !== $current_key) {
                $array[$current_key] = $col;
            }
        }
        return $array;
    }

    public static function modifyAttrKeys(&$collection, $relation_name = '', $key = 'slug') {

        #Helper::tad($collection);
        $array = array();

        foreach ($collection->attributes[$relation_name] as $c => $col) {
            $current_key = is_object($col) ? $col->$key : @$col[$key];
            if (NULL !== $current_key) {
                $array[$current_key] = $col;
            }
        }
        #return $array;
        unset($collection->attributes[$relation_name]);
        $collection->attributes[$relation_name] = $array;

        #Helper::dd($collection);
    }

    /**
     * В функцию передается коллекция объектов, полученная из Eloguent методом ->get(),
     * которая имеет в себе некоторую коллекцию прочих объектов, полученную через связь hasMany (с помощью ->with('...')).
     * Пример: словарь со значениями - Dic::where('slug', 'dicname')->with('values')->get();
     * Функция возвращает массив, ключами которого являются исходные ключи родительской коллекции, а в значение заносится
     * массив, генерирующийся по принципу метода ->lists('name', 'id'), но без дополнительных запросов к БД.
     * Если $listed_key = false, то вместо вложенной коллекции будет перебираться родительская, на предмет поиска соответствий.
     *
     * @param object $collection - Eloquent Collection
     * @param string $listed_key - Key of the child collection, may be false
     * @param string $value
     * @param string $key
     * @return array
     *
     * @author Alexander Zelensky
     */
    public static function makeLists($collection, $listed_key = 'values', $value, $key = '', $hasOne = false) {

        #Helper::ta($collection);

        #$lists = new Collection;
        $lists = array();

        if (!isset($collection) || (!is_array($collection) && !is_object($collection)))
            return $lists;

        foreach ($collection as $c => $col) {

            if (!$listed_key) {

                #Helper::d((int)$col->$value);

                if (isset($col->$value))
                    if ($key != '')
                        $lists[$col->$key] = $col->$value;
                    else
                        $lists[] = $col->$value;

            } else {


                if (!$hasOne) {

                    /**
                     * Если использовалась связь hasMany
                     */
                    $list = array();
                    if (isset($col->$listed_key) && count($col->$listed_key)) {
                        foreach ($col->$listed_key as $e => $el) {
                            if ($key != '') {
                                $list[$el->$key] = $el->$value;
                            } else {
                                $list[] = $el->$value;
                            }
                        }
                    }
                    $lists[$c] = $list;

                } else {

                    /**
                     * Если использовалась связь hasOne
                     */
                    if (isset($col->$listed_key) && is_object($col->$listed_key)) {
                        #Helper::d($col->$listed_key);
                        #Helper::d($key . ' => ' . $value);
                        #$col->$listed_key->attributes[$key]
                        if ($key != '') {
                            $lists[$col->$listed_key->attributes[$key]] = @$col->$listed_key->attributes[$value];
                        } else {
                            $lists[] = @$col->$listed_key->attributes[$value];
                        }
                    }
                }
            }
            #Helper::ta($col);
        }
        #Helper::dd($lists);
        return $lists;
    }


    /**
     * "Ленивая загрузка" данных без использования связи
     *
     * @param $collection
     * @param $key
     * @param $relation_array
     * @return mixed
     */
    public static function custom_load_hasOne($collection, $key, $relation_array, Closure $additional_rules = NULL) {

        $model = $relation_array[0];
        $local_id = $relation_array[1];
        $remote_id = $relation_array[2];

        $list = self::makeLists($collection, null, $local_id);
        #Helper::d($list);

        $values = new Collection;
        if (count($list)) {
            $values = $model::whereIn($remote_id, $list);
            if (is_callable($additional_rules)) {
                #$values = $additional_rules($values);
                /**
                 * Правильный способ применения доп. условий через функцию-замыкание
                 */
                call_user_func($additional_rules, $values);
            }
            $values = $values->get();

            $values = Dic::modifyKeys($values, 'id');
            #Helper::tad($values);
        }

        foreach($collection as $e => $element) {

            if (isset($element->$local_id) && isset($values[$element->$local_id])) {

                /**
                 * Правильная кастомная установка поля.
                 * Доп. поле должно устанавливаться как связь (relation)
                 */
                $element->relations[$key] = @$values[$element->$local_id] ?: NULL;

                /**
                 * Правильное обновление значения элемента коллекции
                 */
                $collection->put($e, $element);
            }
        }

        unset($list);
        unset($values);

        return $collection;
    }





    /**
     * Возвращает записи в словаре по его системному имени, имеет множество настроек.
     * Вторым параметром передается функция-замыкание с доп. условиями выборки, аналогичная по синтаксису доп. условия при вызове связи.
     *
     * @param $slug
     * @param callable $conditions
     * @param string $with - array with relations which will be loaded
     * @param bool $extract - extract or not extract?
     * @param bool $unset - unset old data of the extracted fields
     * @param bool $paginate - false / (int)10
     * @return $this|Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\Paginator|static|static[]
     */
    public static function valuesBySlug($slug, Closure $conditions = NULL, $with = 'all', $extract = true, $unset = true, $extract_ids = false, $paginate = false) {

        $return = new Collection();

        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic))
            return $return;

        $values = (new DicVal());
        #$tbl_dicval = $values->getTable();
        $values = $values
            ->where('dic_id', $dic->id)
            ->where('version_of', NULL)
            #->select($tbl_dicval.'.*')
        ;

        /**
         * Дополнительные условия в функции-замыкании
         */
        if (is_callable($conditions))
            call_user_func($conditions, $values);

        if ($with == 'all')
            $with = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];
        else
            $with = (array)$with;

        if (count($with))
            $values = $values->with($with);

        $values = $paginate ? $values->paginate((int)$paginate) : $values->get();

        if (count($values) && $extract)
            $values = DicLib::extracts($values, null, $unset, $extract_ids);

        return $values;
    }


    /**
     * Возвращает количество записей в словаре
     *
     * @param $slug
     * @param callable $conditions
     * @return bool|int
     */
    public static function valuesBySlugCount($slug, Closure $conditions = NULL) {

        /**
         * Словарь
         */
        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic))
            return false;

        $values = DicVal::where('dic_id', $dic->id)->where('version_of', NULL);

        /**
         * Дополнительные условия в функции-замыкании
         */
        if (is_callable($conditions))
            call_user_func($conditions, $values);

        $count = $values->count();
        return $count;
    }


    /**
     * Возвращает значение записи из словаря по системному имени словаря и системному имени записи.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт записи.
     *
     * @param $slug
     * @param $val_slug
     * @param string $with
     * @param bool $extract
     * @param bool $unset
     * @return $this|Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function valueBySlugs($slug, $val_slug, $with = 'all', $extract = true, $unset = true) {

        $return = new Collection();

        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic))
            return $return;

        $value = DicVal::where('dic_id', $dic->id)
            ->where('version_of', NULL)
            ->where('slug', $val_slug)
        ;

        if ($with == 'all')
            $with = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];
        else
            $with = (array)$with;

        if (count($with))
            $value = $value->with($with);

        $value = $value->first();

        if ($extract && is_object($value))
            $value->extract($unset);

        return $value;
    }


    /**
     * Возвращает значения записей из словаря по системному имени словаря и списку системных имен записей.
     *
     * @param $slug
     * @param $val_slugs
     * @param string $with
     * @param bool $extract
     * @param bool $unset
     * @return $this|Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function valuesBySlugs($slug, $val_slugs, $with = 'all', $extract = true, $unset = true) {

        $return = new Collection();

        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic))
            return $return;

        $values = DicVal::where('dic_id', $dic->id)
            ->where('version_of', NULL)
            ->whereIn('slug', $val_slugs)
        ;

        if ($with == 'all')
            $with = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];
        else
            $with = (array)$with;

        if (count($with))
            $values = $values->with($with);

        $values = $values->get();

        if (is_object($values) && $values->count()) {

            if ($extract)
                $values = DicLib::extracts($values, null, $unset, false);

            foreach ($values as $v => $value) {
                $values[$value->slug] = $value;
                unset($values[$v]);
            }
        }

        #Helper::tad($values);

        return $values;
    }


    /**
     * Возвращает значение записи из словаря по системному имени словаря и ID записи.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт записи.
     *
     * @param $slug
     * @param $val_id
     * @param string $with
     * @param bool $extract
     * @param bool $unset
     * @return $this|Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function valueBySlugAndId($slug, $val_id, $with = 'all', $extract = true, $unset = true) {

        $return = new Collection();

        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic))
            return $return;

        $value = DicVal::where('dic_id', $dic->id)
            ->where('version_of', NULL)
            ->where('id', $val_id)
        ;

        if ($with == 'all')
            $with = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];
        else
            $with = (array)$with;

        if (count($with))
            $value = $value->with($with);

        $value = $value->first();

        if ($extract && is_object($value))
            $value->extract($unset);

        return $value;
    }


    /**
     * Возвращает записи из словаря по системному имени словаря и набору IDs нужных записей.
     * Третьим параметром можно передать метку, указывающую на необходимость сделать экстракт каждой записи.
     *
     * @param $slug
     * @param $val_ids
     * @param string $with
     * @param bool $extract
     * @param bool $unset
     * @return $this|Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|static
     */
    public static function valuesBySlugAndIds($slug, $val_ids, $with = 'all', $extract = true, $unset = true, $extract_ids = false) {

        $return = new Collection();

        #$dic = Dic::where('slug', $slug)->first();
        #$dic = @Config::get(self::$cache_key)['by_slug'][$slug];
        $dic = Dic::by_slug($slug);

        if (!is_object($dic) || !is_array($val_ids) || !count($val_ids))
            return $return;

        $values = DicVal::where('dic_id', $dic->id)
            ->where('version_of', NULL)
            ->whereIn('id', $val_ids)
        ;

        if ($with == 'all')
            $with = ['meta', 'fields', 'textfields', 'seo', 'related_dicvals'];
        else
            $with = (array)$with;

        if (count($with))
            $values = $values->with($with);

        $values = $values->get();

        #Helper::tad($values);

        if ($extract)
            $values = DicLib::extracts($values, null, $unset, $extract_ids);

        #Helper::tad($values);

        return $values;
    }



    /**
     * Предзагрузка всех словарей и кеширование
     */
    public static function preload() {

        $cache_key = self::$cache_key;
        #$cache_pages_limit = Config::get('pages.preload_dics_limit');

        if (Cache::has($cache_key) && !Input::get('drop_dics_cache')) {

            ## From cache
            $dics = Cache::get($cache_key);

        } else {

            #echo "LOAD DICS FROM DB!";

            ## From DB
            $dics = (new Dic())->get();

            if (isset($dics) && is_object($dics) && count($dics)) {
                $dics_by_slug = [];
                $dics_by_id = [];
                foreach ($dics as $d => $dic) {
                    #$dic->extract(1);
                    $dics_by_slug[$dic->slug] = $dic;
                    $dics_by_id[$dic->id] = $dic;
                }
                $dics = ['by_slug' => $dics_by_slug, 'by_id' => $dics_by_id];
            }
        }

        ## Save cache
        $cache_lifetime = Config::get('site.dics.preload_cache_lifetime') ?: NULL;
        if ($cache_lifetime) {
            $expiresAt = Carbon::now()->addMinutes($cache_lifetime);
            Cache::put($cache_key, $dics, $expiresAt);
        }

        Config::set($cache_key, $dics);

        #Helper::tad($dics);
    }

    public static function drop_cache() {

        $cache_key = self::$cache_key;
        Config::set($cache_key, NULL);
        Cache::forget($cache_key);
    }


    public static function all_by_slug() {
        $cache_key = self::$cache_key;
        $dics = Config::get($cache_key);
        $dics = @$dics['by_slug'];
        return $dics ?: NULL;
    }

    public static function all_by_id() {
        $cache_key = self::$cache_key;
        $dics = Config::get($cache_key);
        $dics = @$dics['by_id'];
        return $dics ?: NULL;
    }


    public static function by_slug($slug) {
        $cache_key = self::$cache_key;
        $dics = Config::get($cache_key);
        $dic = @$dics['by_slug'][$slug];
        return $dic ?: NULL;
    }

    public static function by_id($id) {
        $cache_key = self::$cache_key;
        $dics = Config::get($cache_key);
        $dic = @$dics['by_id'][$id];
        return $dic ?: NULL;
    }

    /**
     * DEPRECATED
     * Устаревшие и не рекомендуемые к использованию методы
     */
    public static function whereSlugValues($slug) {
        return self::firstOrNew(array('slug' => $slug))->values;
    }


}

class Dictionary extends Dic {
    ## Alias
}