<?php

/**
 * PaymentStatus
 *
 * @property integer $id
 * @property string $title
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\PaymentStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\PaymentStatus whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\PaymentStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\PaymentStatus whereUpdatedAt($value)
 */
class PaymentStatus extends BaseModel {

    protected $guarded = array();

    protected $table = 'payment_status';

    protected $fillable = array('title');

    public static $order_by = "title";

    public static $rules = array(
        'title' => 'required',
    );

}