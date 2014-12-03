<?php

class User_settings extends BaseModel {

    protected $table = 'user_settings';

    protected $guarded = array();

    public function user(){

        return $this->hasMany('User','user_id');
    }
}