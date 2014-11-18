<?php

/**
 * CourseMetodical
 *
 * @property integer $id
 * @property integer $direction_id
 * @property integer $order
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $curriculum
 * @property float $price
 * @property boolean $discount
 * @property integer $hours
 * @property integer $metodical
 * @property integer $certificate
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Upload $document
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereDirectionId($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereCurriculum($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereHours($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereMetodical($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CourseMetodical whereUpdatedAt($value)
 */
class CourseMetodical extends BaseModel {

    protected $guarded = array();

    protected $table = 'course_metodical';

    protected $fillable = array('order','course_id','title','description','document_id');

    public static $rules = array(
        'course_id' => 'required',
        'title' => 'required',
    );
    public function document(){
        return $this->hasOne('Upload','id','document_id');
    }
}