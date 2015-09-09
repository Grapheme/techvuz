<?php

/**
 * Courses
 *
 * @property integer $id
 * @property integer $direction_id
 * @property integer $sort
 * @property string $code
 * @property string $title
 * @property string $description
 * @property float $price
 * @property integer $hours
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Directions $direction
 * @method static \Illuminate\Database\Query\Builder|\Courses whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereDirectionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereHours($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Courses whereUpdatedAt($value)
 */
class Courses extends BaseModel {

    protected $guarded = array();

    protected $table = 'courses';

    protected $fillable = array('direction_id', 'order', 'code', 'title', 'test_title', 'test_hours','test_questions_count', 'description',
        'price', 'discount', 'use_discount', 'hours', 'certificate', 'curriculum', 'active', 'in_progress');

    public static $order_by = "order";

    public static $rules = array(
        'direction_id' => 'required',
        'code' => 'required',
        'title' => 'required',
    );

    public function direction() {
        return $this->belongsTo('Directions', 'direction_id');
    }

    public function metodicals() {
        return $this->hasMany('CourseMetodical', 'course_id');
    }

    public function chapters() {
        return $this->hasMany('Chapter', 'course_id');
    }

    public function lectures() {
        return $this->hasMany('Lectures', 'course_id');
    }

    public function test() {
        return $this->hasOne('CoursesTests', 'course_id')->where('chapter_id', 0)->where('trial_test', 0);
    }

    public function trial_test() {
        return $this->hasOne('CoursesTests', 'course_id')->where('chapter_id', 0)->where('trial_test', 1);
    }

    public function seo() {
        return $this->hasOne('Seo', 'unit_id', 'id')->where('module', 'education-courses');
    }

    public function certificate() {
        return $this->hasOne('DicVal', 'id');
    }
}