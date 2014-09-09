<?php


/**
 * Lectures
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $chapter_id
 * @property integer $order
 * @property string $title
 * @property string $description
 * @property integer $document
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Courses $course
 * @property-read \Courses $chapter
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereCourseId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereChapterId($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereDocument($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Lectures whereUpdatedAt($value)
 */
class Lectures extends BaseModel {

    protected $guarded = array();

    protected $table = 'lectures';

    protected $fillable = array('course_id','chapter_id','order','title','description','document');

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
    public function document(){
        return $this->hasOne('Upload','id','document');
    }
}