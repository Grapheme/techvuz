<?php

class Courses extends BaseModel {

    protected $guarded = array();

    protected $table = 'courses';

    protected $fillable = array('direction_id','sort','code','title','description','price','hours','libraries','curriculum','metodical');

    public static $order_by = "sort";

    public static $rules = array(
        'direction_id' => 'required',
        'code' => 'required',
        'title' => 'required',
    );

    public function direction() {
        return $this->belongsTo('Directions', 'direction_id');
    }

}