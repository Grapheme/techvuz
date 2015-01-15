<?php

class myDateTime {

    protected $date_string;
    protected $months;

    public function __construct($string = NULL){

        if (!is_null($string)):
            $this->date_string = \Carbon\Carbon::createFromTimestamp($string);
        else:
            $this->date_string = \Carbon\Carbon::now();
        endif;
        $this->months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
    }

    public function setDateString($string){

        $this->date_string = \Carbon\Carbon::createFromTimestamp(strtotime($string));
        return $this;
    }

    public function setMonths($list){

        $this->months = $list;
        return $this;
    }

	public static function SwapDotDateWithTime($date_time) {
		$list = preg_split("/-/",$date_time);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1 в \$6:\$8";
		return preg_replace($pattern, $replacement,$date_time);
	}

    public static function SwapDotDateWithOutTime($date_time) {

        if ($date_time != '0000-00-00 00:00:00'):
            $list = preg_split("/-/",$date_time);
            $pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
            $replacement = "\$5.$3.\$1";
            return preg_replace($pattern, $replacement,$date_time);
        else:
            return '';
        endif;
	}
	
	public function months(){

        if ($this->validDate()):
            $list = explode("-",$this->date_string);
            $list[2] = (int)$list[2];
            $field = implode("-",$list);
            $nmonth = $this->months[$list[1]];
            $pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
            $replacement = "\$5 $nmonth \$1 г.";
            return preg_replace($pattern, $replacement,$field);
        endif;
	}
	
	public function monthsTime(){

        if ($this->validDate()):
            $list = explode("-",$this->date_string);
            $list[2] = (int)$list[2];
            $time = substr($this->date_string,11);
            $field = implode("-",$list).' '.$time;
            $nmonth = $this->months[$list[1]];
            $pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
            $replacement = "\$5 $nmonth \$1 в \$6:\$8";
            return preg_replace($pattern, $replacement,$field);
        endif;
	}

	public static function getFutureDays($days = 1,$date = NULL){
		
		if(is_null($date)):
			return (time()+($days*24*60*60));
		else:
			return (strtotime($date)+($days*24*60*60));
		endif;
	}

	public static function getNewsDate($date_time){
		
		$list = preg_split("/-/",$date_time);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5/\$3/\$1";
		return preg_replace($pattern, $replacement,$date_time);
	}

	public static function getDayAndMonth($date_time){
		
		$list = preg_split("/-/",$date_time);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5/$3";
		return preg_replace($pattern, $replacement,$date_time);
	}

    public static function getDiffDate($date_begin,$date_over,$format = '%d%'){

        $datetime1 = new DateTime($date_begin);
        $datetime2 = new DateTime($date_over);
        $interval = $datetime1->diff($datetime2);
        if (is_null($format)):
            return (array) $interval;
        else:
            return (int) $interval->format($format);
        endif;

    }

    public static function getDiffTimeStamp($date_begin,$date_over){

        return strtotime($date_begin) - strtotime($date_over);
    }

    public function addDays($days = 0){

        $this->date_string->addDays($days);
        return $this;
    }

    public function format($format){

        if ($this->validDate()):
            return $this->date_string->format($format);
        else:
            return '';
        endif;
    }

    private function validDate(){

        if ($this->date_string == '1970-01-01 00:00:00' || strtotime($this->date_string) < 10000):
            return FALSE;
        else:
            return TRUE;
        endif;
    }
}