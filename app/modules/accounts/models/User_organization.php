<?php

class User_organization extends BaseModel {

    protected $table = 'users_organizations';

    protected $guarded = array();

    public function bank_account_type(){

        return $this->hasOne('AccountTypes','id','account_type_id');
    }
    public function listeners(){

        return $this->hasMany('User_listener','organization_id');
    }

    public function orders(){

        return $this->hasMany('Orders','user_id');
    }
}