<?php

/**
 * OrderPayments
 *
 * @property integer $id
 * @property integer $order_id
 * @property float $price
 * @property string $payment_number
 * @property string $payment_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Orders $order
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments wherePaymentNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments wherePaymentDate($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderPayments whereUpdatedAt($value)
 */
class OrderPayments extends BaseModel {

    protected $guarded = array();

    protected $table = 'order_payments';

    protected $fillable = array('order_id','price','payment_number','payment_date');

    public static $order_by = "payment_number";

    public static $rules = array(
        'order_id' => 'required',
        'price' => 'required',
        'payment_number' => 'required',
        'payment_date' => 'required',
    );

    public function order() {
        return $this->belongsTo('Orders', 'order_id');
    }
}