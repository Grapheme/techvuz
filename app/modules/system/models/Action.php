<?php

class Action extends BaseModel {

	protected $guarded = array();
	protected $table = 'actions';
    public $timestamps = false;

	public function group(){
		return $this->hasOne('Group', 'group_id', 'id');
	}

	public function module(){
		return $this->hasOne('Modules', 'module', 'name');
	}

}