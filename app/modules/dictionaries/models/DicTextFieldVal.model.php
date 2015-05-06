<?php

class DicTextFieldVal extends BaseModel {

	protected $guarded = array();

	public $table = 'dictionary_textfields_values';
    #public $timestamps = false;

	#public static $order_by = "name ASC";

    protected $fillable = array(
        'dicval_id',
        'language',
        'key',
        'value',
    );

	public static $rules = array(
        'dicval_id' => 'required',
        'key' => 'required',
	);

}