<?php


class CoursesTrialTests extends BaseModel {

    protected $guarded = array();

    protected $table = 'trial_tests';

    protected $fillable = array('course_id','order','title','description','active');

    public static $order_by = "order";

    public static $rules = array(
        'course_id' => 'required',
        'title' => 'required',
    );

    public function course(){
        return $this->belongsTo('Courses','course_id');
    }

    public function questions(){
        return $this->hasMany('CoursesTestsQuestions','test_id');
    }

    public function answers(){
        return $this->hasMany('CoursesTestsAnswers','test_id');
    }

}