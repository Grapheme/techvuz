<?php

class Dictionary extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary';
    #public $timestamps = false;

	public static $order_by = "name ASC";

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

    #public static function rules() {
    #    return self::$rules;
    #}

    public function values() {
        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->where('version_of', NULL)
            ->with('meta', 'fields')
            ->orderBy('order', 'ASC')
            ->orderBy('slug', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('id', 'ASC')
        ;
    }

    public function values_no_conditions() {
        return $this->hasMany('DicVal', 'dic_id', 'id')
            ->where('version_of', NULL)
            ->with('meta', 'fields')
        ;
    }

    public function values_count() {
        return DicVal::where('dic_id', $this->id)->where('version_of', NULL)->count();
    }

    public function values_count2() {
        return $this->hasMany('DicVal', 'dic_id', 'id')->where('version_of', NULL); #->select(DB::raw('COUNT(*) as count'));
    }

    public function value() {
        return $this->hasOne('DicVal', 'dic_id', 'id')->where('version_of', NULL);
    }

    public static function whereSlugValues($slug) {
        return self::firstOrNew(array('slug' => $slug))->values;
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
        #$array = array();
        $array = new Collection;
        foreach ($collection as $c => $col) {
            if (NULL !== ($current_key = $col->$key)) {
                $array[$current_key] = $col;
            }
        }
        return $array;
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
    public static function makeLists($collection, $listed_key = 'values', $value, $key = '') {
        #Helper::ta($collection);
        #$lists = new Collection;
        $lists = array();
        foreach ($collection as $c => $col) {
            if (!$listed_key) {

                #Helper::d((int)$col->$value);

                if (isset($col->$value))
                    if ($key != '')
                        $lists[$col->$key] = $col->$value;
                    else
                        $lists[] = $col->$value;

            } else {

                $list = array();
                if (isset($col->$listed_key) && count($col->$listed_key))
                    #Helper::ta($col->$listed_key);
                    foreach ($col->$listed_key as $e => $el) {
                        #Helper::d("$e => $el");
                        if ($key != '')
                            $list[$el->$key] = $el->$value;
                        else
                            $list[] = $el->$value;
                    }
                    #Helper::dd($list);
                $lists[$c] = $list;
            }
            #Helper::ta($col);
        }
        #Helper::dd($lists);
        return $lists;
    }


    ## Need to check
    public function valueBySlug($slug) {
        return $this->with(array('value' => function($query) use ($slug) {
                $query->whereSlug($slug);
            }))->first()->value;
    }

    public static function valuesBySlug($slug, Closure $conditions = NULL) {
        #Helper::dd($slug);
        $return = Dic::where('slug', $slug);
        #dd($conditions);
        if (is_callable($conditions))
            $return = $return->with(array('values_no_conditions' => $conditions));
        else
            $return = $return->with('values');

        $return = $return->first();

        #Helper::tad($return);

        if (is_object($return))
            $return = isset($return->values_no_conditions) ? $return->values_no_conditions : $return->values;
        else
            $return = Dic::firstOrNew(array('slug' => $slug, 'version_of' => NULL))->with('values')->first()->values;
        #return self::firstOrNew(array('slug' => $slug))->values;
        return $return;
    }

    ## Work cool
    public static function valueBySlugs($dic_slug, $val_slug, $extract = false) {

        #Helper::d("$dic_slug, $val_slug");

        $data = self::where('slug', $dic_slug)->with(array('value' => function($query) use ($val_slug){
                $query->where('version_of', NULL);
                $query->where('slug', $val_slug);
                $query->with('meta', 'fields', 'seo', 'related_dicvals');
            }))->first()->value;

        if ($extract) {
            $data->extract(0);
        }

        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }

    public static function valueBySlugAndId($dic_slug, $val_id, $extract = false) {

        $data = self::where('slug', $dic_slug)->with(array('value' => function($query) use ($val_id){
                $query->where('version_of', NULL);
                $query->where('id', $val_id);
                $query->with('meta', 'fields', 'seo', 'related_dicvals');
            }))
            ->first()
            ->value
        ;
        #Helper::tad($data);

        if ($extract)
            $data->extract(0);
        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }

    public static function valuesBySlugAndIds($dic_slug, $val_ids, $extract = false) {

        $data = self::where('slug', $dic_slug)->with(array('values_no_conditions' => function($query) use ($val_ids){
                $query->where('version_of', NULL);
                $query->whereIn('id', $val_ids);
                $query->with('meta', 'fields', 'seo', 'related_dicvals');
            }))
            ->first()
            ->values_no_conditions
        ;
        #Helper::tad($data);

        /*
        ## Need to test
        if ($extract)
            $data->extract(0);
        */
        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }



}

class Dic extends Dictionary {
    ## Alias
}