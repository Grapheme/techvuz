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
        'name_title',
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

    public function value() {
        return $this->hasOne('DicVal', 'dic_id', 'id');
    }

    public static function whereSlugValues($slug) {
        return self::firstOrNew(array('slug' => $slug))->values;
    }

    ## Need to check
    public function valueBySlug($slug) {
        return $this->with(array('value' => function($query) use ($slug) {
                    $query->whereSlug($slug);
                }))->first()->value;
    }

    public static function valuesBySlug($slug) {
        $return = Dic::where('slug', $slug)->with('values')->first()->values;
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