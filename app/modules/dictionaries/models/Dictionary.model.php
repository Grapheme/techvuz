<?php

/**
 * Dictionary
 *
 * @property integer $id
 * @property string $slug
 * @property string $name
 * @property boolean $entity
 * @property string $icon_class
 * @property boolean $hide_slug
 * @property string $name_title
 * @property integer $order
 * @property-read \Illuminate\Database\Eloquent\Collection|\DicVal[] $values
 * @property-read \DicVal $value
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereSlug($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereEntity($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereIconClass($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereHideSlug($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereNameTitle($value) 
 * @method static \Illuminate\Database\Query\Builder|\Dictionary whereOrder($value) 
 */
class Dictionary extends BaseModel {

	protected $guarded = array();

    public $table = 'dictionary';
    public $timestamps = false;

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