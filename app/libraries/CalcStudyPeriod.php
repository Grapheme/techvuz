<?php

class CalcStudyPeriod {

    protected $current_hours;
    protected $prev_hours;
    protected $hours;
    protected $start_date;
    protected $date_begin;
    protected $date_end;
    private $format;
    private $days;
    private $prev_days;

    public function __construct(){
        self::setFormat();
        $this->date_end = new \Carbon\Carbon();
    }

    public function config($config){

        $this->hours = 0 ;
        $this->start_date = isset($config['start_date']) ? \Carbon\Carbon::createFromTimestamp(strtotime($config['start_date'])) : new \Carbon\Carbon();
        return $this;
    }

    public function setFormat($format = 'd.m.y'){

        $this->format = $format;
    }

    public function setBeginDateString($string){

        $this->date_begin = \Carbon\Carbon::createFromTimestamp(strtotime($string));
        return $this;
    }

    public function setEndDateString($string){

        $this->date_end = \Carbon\Carbon::createFromTimestamp(strtotime($string));
        return $this;
    }

    public function addHours($hours){

        $this->current_hours = $hours;
        $this->prev_hours = $this->hours;
        $this->hours += $hours;
        self::_createDate();
        return $this;
    }

    public function write(){

        if($this->hours % 8 == 0):
            return $this->date_begin->format($this->format);
        elseif($this->date_begin == $this->date_end || ($this->prev_hours > 0 && ($this->prev_hours % 8) == 0)):
            return $this->date_end->format($this->format);
        else:
            return $this->date_begin->format($this->format).'-'.$this->date_end->format($this->format);
        endif;
    }

    private function _createDate(){

        $this->days = floor($this->hours/8);
        $this->prev_days = floor($this->prev_hours/8);
        $this->date_begin = \Carbon\Carbon::createFromTimestamp(strtotime($this->start_date));
        $this->date_end = \Carbon\Carbon::createFromTimestamp(strtotime($this->start_date));
        if ($this->prev_days > 0):
            $this->date_begin = $this->date_begin->addDays($this->prev_days);
        endif;
        if ($this->days > 0):
            $this->date_end->addDays($this->days);
        endif;
    }

    public function get($item){

        return $this->$item;
    }

}