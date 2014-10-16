<?php

class Setting extends BaseModel {
	
	protected $guarded = array();
    public $table = 'settings';

    protected $fillable = array(
        'module',
        'name',
        'value',
    );

    public static $rules = array(
        'name' => 'required',
    );
}