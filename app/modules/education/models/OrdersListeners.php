<?php

/**
 * OrderListeners
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $course_id
 * @property integer $user_id
 * @property float $price
 * @property boolean $start_status
 * @property boolean $over_status
 * @property string $start_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Order $order
 * @property-read \Courses $course
 * @property-read \User $listener
 * @property-read \Illuminate\Database\Eloquent\Collection|\OrderListenersTests[] $listener_tests
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereStartStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereOverStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\OrderListeners whereUpdatedAt($value)
 */
class OrderListeners extends BaseModel {

    protected $guarded = array();

    protected $table = 'order_listeners';

    protected $fillable = array('order_id','course_id','user_id','price');

    public static $rules = array();

    public function order() {
        return $this->belongsTo('Orders', 'order_id');
    }

    public function course() {
        return $this->belongsTo('Courses', 'course_id');
    }

    public function listener() {
        return $this->belongsTo('User', 'user_id');
    }

    public function listener_tests() {
        return $this->hasMany('OrderListenersTests', 'order_listeners_id');
    }

    public function user_listener() {
        return $this->belongsTo('User_listener', 'user_id');
    }

}