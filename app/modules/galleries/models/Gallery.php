<?php

class Gallery extends Eloquent {
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required|unique:galleries',
	);

	public static function getRules() {
		return self::$rules;
	}

	public function photos() {
		return $this->hasMany('Photo');
	}

}