<?php

class User_listener extends BaseModel {

    protected $table = 'users_listeners';

    protected $guarded = array();

    public function study(){

        return $this->hasMany('OrderListeners','user_id');
    }
}