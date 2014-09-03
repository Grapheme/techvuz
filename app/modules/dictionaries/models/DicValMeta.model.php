<?php

class DicValMeta extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_values_meta';
    public $timestamps = false;

	public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_id',
        'language',
        'name',
    );

	public static $rules = array(
		#'name' => 'required',
	);

    #public static function rules() {
    #    return self::$rules;
    #}

    public function dicval() {
        return $this->belongsTo('DicVal', 'dicval_id');
    }

}