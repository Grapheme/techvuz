<?php

/**
 * CoursesTestsQuestions
 *
 * @property integer $id
 * @property integer $test_id
 * @property integer $order
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \CoursesTests $test
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereTestId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsQuestions whereUpdatedAt($value)
 */
class CoursesTestsQuestions extends BaseModel {

    protected $guarded = array();

    protected $table = 'tests_questions';

    protected $fillable = array('test_id','order','title','description');

    public static $order_by = "order";

    public static $rules = array(
        'test_id' => 'required',
        'title' => 'required',
    );

    public function test(){
        return $this->belongsTo('CoursesTests','test_id');
    }
}