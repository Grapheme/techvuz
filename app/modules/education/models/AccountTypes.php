<?php

class AccountTypes extends BaseModel {

    protected $guarded = array();

    protected $table = 'account_types';

    protected $fillable = array('title');

    public static $order_by = "title";

    public static $rules = array(
        'title' => 'required',
    );

}