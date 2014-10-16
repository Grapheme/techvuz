<?php

class Module extends BaseModel {
	
	protected $guarded = array();

	protected $table = 'modules';

    public function actions() {

        return $this->hasMany('Action', 'module', 'name')->where('status', '1')->where('group_id', Auth::user() ? Auth::user()->group->id : 0);
    }
}