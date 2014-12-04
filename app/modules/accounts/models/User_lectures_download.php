<?php

class User_lectures_download extends BaseModel {

    protected $table = 'user_download_lectures';

    protected $guarded = array();

    public function user(){

        return $this->hasMany('User','user_id');
    }

    public function lecture(){

        return $this->hasMany('Lectures','lecture_id');
    }
}