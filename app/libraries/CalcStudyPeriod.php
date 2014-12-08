<?php

class CalcStudyPeriod {

    protected $hours;
    protected $hours_total;
    protected $start_date;
    protected $over_date;
    protected $date_begin;
    protected $date_end;
    private $format;
    private $days;
    private $hours_summa;

    public function __construct(){
        self::setFormat();
        $this->over_date = new \Carbon\Carbon();
        $this->date_end = new \Carbon\Carbon();
    }

    public function config($config){

        $this->hours = isset($config['hours']) ? $config['hours'] : 0 ;
        $this->hours_total = isset($config['hours_total']) ? $config['hours_total'] : 72 ;
        $this->start_date = isset($config['start_date']) ? \Carbon\Carbon::createFromTimestamp(strtotime($config['start_date'])) : new \Carbon\Carbon();
        $this->date_begin =  $this->start_date;
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

        $this->hours = $hours;
        $this->hours_summa += $hours;
        self::_createDate();
        return $this;
    }

    public function write(){

        $begin = $this->date_begin->format($this->format);
        $end = $this->date_end->format($this->format);
        #if ($begin == $end):
        #    return $this->hours_summa.' '.$this->days.' '.$begin;
        #else:
            return $this->hours_summa.' '.$this->days.' '.$this->date_begin->format($this->format).'-'.$this->date_end->format($this->format);
        #endif;
    }

    private function _createDate(){

        $this->days = floor($this->hours_summa/8);
        if ($this->days > 0):

            Helper::d($this->date_begin);
            Helper::d($this->date_end);
            $this->date_begin = $this->date_end;
            $this->date_end = $this->start_date->addDays($this->days);
        else:
            $this->date_end = $this->date_end->addDays($this->days);
        endif;
    }

}