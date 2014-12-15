<?php

class User_individual extends BaseModel {

    protected $table = 'users_individuals';

    protected $guarded = array();

    public function orders(){

        return $this->hasMany('Orders','user_id');
    }

    public function study(){

        return $this->hasMany('OrderListeners','user_id');
    }
}