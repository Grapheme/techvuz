<?php

/**
 * CoursesTestsAnswers
 *
 * @property integer $id
 * @property integer $test_id
 * @property integer $test_question_id
 * @property integer $order
 * @property string $title
 * @property string $description
 * @property integer $correct
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \CoursesTests $test
 * @property-read \CoursesTestsQuestions $question
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereTestId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereTestQuestionId($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereCorrect($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CoursesTestsAnswers whereUpdatedAt($value)
 */
class CoursesTestsAnswers extends BaseModel {

    protected $guarded = array();

    protected $table = 'tests_answers';

    protected $fillable = array('test_id','test_question_id','order','title','description','correct');

    public static $order_by = "order";

    public static $rules = array(
        'test_id' => 'required',
        'test_question_id' => 'required',
        'title' => 'required',
    );

    public function test(){
        return $this->belongsTo('CoursesTests','test_id');
    }

    public function question(){
        return $this->belongsTo('CoursesTestsQuestions','test_question_id');
    }
}