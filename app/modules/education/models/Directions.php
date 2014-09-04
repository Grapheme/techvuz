<?php

class Directions extends BaseModel {

    protected $guarded = array();

    protected $table = 'directions';

    protected $fillable = array('sort','code','title','description');

    public static $order_by = "sort";

    public static $rules = array(
        'code' => 'required',
        'title' => 'required',
    );

    public function courses() {
        return $this->hasMany('Courses', 'direction_id', 'id');
    }

}