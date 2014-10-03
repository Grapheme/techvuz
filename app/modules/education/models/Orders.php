<?php

/**
 * Orders
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $number
 * @property boolean $completed
 * @property boolean $payment_status
 * @property string $payment_date
 * @property string $payment_date_real
 * @property integer $payment_discount
 * @property boolean $close_status
 * @property string $close_date
 * @property boolean $archived
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\OrderCourses[] $courses
 * @method static \Illuminate\Database\Query\Builder|\Orders whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCompleted($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDateReal($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders wherePaymentDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCloseStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCloseDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereArchived($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Orders whereUpdatedAt($value)
 */
class Orders extends BaseModel {

    protected $guarded = array();

    protected $table = 'orders';

    protected $fillable = array('user_id','number','completed');

    public static $order_by = "number";

    public static $rules = array();

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

    public function courses() {
        return $this->hasMany('OrderCourses', 'order_id');
    }

}