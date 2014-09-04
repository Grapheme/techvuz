<?php

/**
 * DicFieldVal
 *
 * @property integer $id
 * @property integer $dicval_id
 * @property string $language
 * @property string $key
 * @property string $value
 * @method static \Illuminate\Database\Query\Builder|\DicFieldVal whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\DicFieldVal whereDicvalId($value) 
 * @method static \Illuminate\Database\Query\Builder|\DicFieldVal whereLanguage($value) 
 * @method static \Illuminate\Database\Query\Builder|\DicFieldVal whereKey($value) 
 * @method static \Illuminate\Database\Query\Builder|\DicFieldVal whereValue($value) 
 */
class DicFieldVal extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_fields_values';
    public $timestamps = false;

	#public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_id',
        'language',
        'key',
        'value',
    );

	public static $rules = array(
		'value_id' => 'required',
	);

    #public static function rules() {
    #    return self::$rules;
    #}

}