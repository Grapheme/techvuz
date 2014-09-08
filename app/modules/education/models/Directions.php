<?php

/**
 * Directions
 *
 * @property integer $id
 * @property integer $sort
 * @property string $code
 * @property string $title
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Courses[] $courses
 * @method static \Illuminate\Database\Query\Builder|\Directions whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereSort($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Directions whereUpdatedAt($value)
 */
class Directions extends BaseModel {

    protected $guarded = array();

    protected $table = 'directions';

    protected $fillable = array('order','code','title','description');

    public static $order_by = "order";

    public static $rules = array(
        'code' => 'required',
        'title' => 'required',
    );

    public function courses() {
        return $this->hasMany('Courses', 'direction_id', 'id');
    }

}