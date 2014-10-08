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
            ->with('meta', 'fields')
            ->orderBy('order', 'ASC')
            ->orderBy('slug', 'ASC')
            ->orderBy('name', 'ASC')
            ->orderBy('id', 'ASC');
    }

    public function values_count() {
        return DicVal::where('dic_id', $this->id)->count();
    }

    public function values_count2() {
        return $this->hasMany('DicVal', 'dic_id', 'id'); #->select(DB::raw('COUNT(*) as count'));
    }

    public function value() {
        return $this->hasOne('DicVal', 'dic_id', 'id');
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
        $array = array();
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
        $lists = array();
        foreach ($collection as $c => $col) {
            if (!$listed_key) {

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

    public static function valuesBySlug($slug) {
        #Helper::dd($slug);
        $return = Dic::where('slug', $slug)->with('values')->first();
        if (is_object($return))
            $return = $return->values;
        else
            $return = Dic::firstOrNew(array('slug' => $slug))->with('values')->first()->values;
        #return self::firstOrNew(array('slug' => $slug))->values;
        return $return;
    }

    ## Work cool
    public static function valueBySlugs($dic_slug, $val_slug, $extract = false) {

        #Helper::d("$dic_slug, $val_slug");

        $data = self::where('slug', $dic_slug)->with(array('value' => function($query) use ($val_slug){
                $query->where('slug', $val_slug);
                $query->with('meta', 'fields');
            }))->first()->value;

        if ($extract) {
            if (@count($data->fields)) {
                foreach ($data->fields as $field) {
                    $data->{$field->key} = $field->value;
                }
                unset($data->fields);
            }
            if (@is_object($data->meta)) {
                if (@$data->meta->name)
                    $data->name = $data->meta->name;
                if (@$data->meta->slug)
                    $data->slug = $data->meta->slug;
                unset($data->meta);
            }
        }

        #Helper::tad($data);

        return is_object($data) ? $data : self::firstOrNew(array('id' => 0));
    }

}

class Dic extends Dictionary {
    ## Alias
}