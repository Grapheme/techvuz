<?php


/**
 * CoursesTests
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $chapter_id
 * @property integer $order
 * @property string $title
 * @property string $description
 * @property integer $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Courses $course
 * @property-read \Chapter $chapter
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereChapterId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTests whereUpdatedAt($value)
 */
class CoursesTests extends BaseModel {

    protected $guarded = array();

    protected $table = 'tests';

    protected $fillable = array('course_id','chapter_id','order','title','description','active','trial_test');

    public static $order_by = "order";

    public static $rules = array(
        'course_id' => 'required',
        'chapter_id' => 'required',
        'title' => 'required',
    );

    public function course(){
        return $this->belongsTo('Courses','course_id');
    }

    public function chapter(){
        return $this->belongsTo('Chapter','chapter_id');
    }

    public function questions(){
        return $this->hasMany('CoursesTestsQuestions','test_id');
    }

    public function answers(){
        return $this->hasMany('CoursesTestsAnswers','test_id');
    }

    public function user_tests(){

        return $this->hasMany('OrdersListenersTests','test_id');
    }

    public function user_test(){

        return $this->hasOne('OrdersListenersTests','test_id');
    }

    public function user_final_test_success(){

        return $this->hasMany('OrdersListenersTests','test_id')->where('chapter_id',0)->where('result_attempt','>=',Config::get('site.success_test_percent'));
    }

    public function user_test_has100(){

        return $this->hasOne('OrdersListenersTests','test_id')->where('result_attempt',100);
    }

    public function user_test_success(){

        return $this->hasOne('OrdersListenersTests','test_id')->where('result_attempt','>=',Config::get('site.success_test_percent'))->where('result_attempt','<',100);
    }
}