<?php

/**
 * OrdersListenersTests
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
 * @property-read \OrderListeners $order_listener
 * @property-read \Chapter $chapter
 * @property-read \CoursesTests $test
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereOrderId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereStartStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereOverStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\OrdersListenersTests whereUpdatedAt($value)
 */
class OrdersListenersTests extends BaseModel {

    protected $guarded = array();

    protected $table = 'order_listener_tests';

    protected $fillable = array('order_listeners_id','chapter_id','test_id','data_results','result_attempt','time_attempt');

    public static $rules = array();

    public function order_listener() {
        return $this->belongsTo('OrderListeners', 'order_listeners_id');
    }

    public function chapter() {
        return $this->belongsTo('Chapter', 'chapter_id');
    }

    public function test() {
        return $this->belongsTo('CoursesTests', 'test_id');
    }

}