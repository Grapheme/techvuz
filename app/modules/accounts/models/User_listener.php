<?php

class User_listener extends BaseModel {

    protected $table = 'users_listeners';

    protected $guarded = array();

    public function study(){

        return $this->hasMany('OrderListeners','user_id');
    }
    public function organization(){

        return $this->belongsTo('User_organization','organization_id');
    }
}