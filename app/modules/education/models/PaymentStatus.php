<?php

class PaymentStatus extends BaseModel {

    protected $guarded = array();

    protected $table = 'payment_status';

    protected $fillable = array('title');

    public static $order_by = "title";

    public static $rules = array(
        'title' => 'required',
    );

}