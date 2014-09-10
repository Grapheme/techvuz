<?php

/**
 * UsersTests
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $test_id
 * @property integer $attempt
 * @property integer $result
 * @property integer $time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \User $user
 * @property-read \CoursesTests $test
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereTestId($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereAttempt($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereResult($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereTime($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\UsersTests whereUpdatedAt($value)
 */
class UsersTests extends BaseModel {

    protected $guarded = array();

    protected $table = 'users_tests';

    protected $fillable = array('user_id','test_id','attempt','result','time');

    public static $order_by = "order";

    public static $rules = array(
        'user_id' => 'required',
        'test_id' => 'required',
    );

    public function user(){
        return $this->belongsTo('User','user_id');
    }

    public function test(){
        return $this->belongsTo('CoursesTests','test_id');
    }

}