<?php


/**
 * Chapter
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $order
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Courses $course
 * @property-read \Illuminate\Database\Eloquent\Collection|\Lectures[] $lectures
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Chapter whereUpdatedAt($value)
 */
class Chapter extends BaseModel {

    protected $guarded = array();

    protected $table = 'chapters';

    protected $fillable = array('course_id','order','title','test_title','description','order','hours');

    public static $order_by = "order";

    public static $rules = array(
        'course_id' => 'required',
        'title' => 'required',
    );

    public function course(){
        return $this->belongsTo('Courses','course_id');
    }

    public function lectures(){
        return $this->hasMany('Lectures','chapter_id');
    }

    public function test(){
        return $this->hasOne('CoursesTests','chapter_id');
    }

}