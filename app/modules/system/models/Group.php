<?php

class Group extends BaseModel {
	
	protected $guarded = array();

	public static $rules = array(
		'name' => 'required|unique:groups',
		'desc' => 'required|unique:groups',
		'dashboard' => 'required'
	);

	public static $rules_update = array(
		'name' => 'required',
		'desc' => 'required',
		'dashboard' => 'required'
	);

    ## Количество юзеров в группе
	public function count_users() {
		return User::where('group_id', $this->id)->count();
	}
	
}