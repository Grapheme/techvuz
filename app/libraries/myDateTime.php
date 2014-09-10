<?php
class myDateTime {
	
	public static function SwapDotDateWithTime($date_time) {
		$list = preg_split("/-/",$date_time);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1 \$6:\$8";
		return preg_replace($pattern, $replacement,$date_time);
	}
	
	public static function months($field,$months = NULL){
		
		if(is_null($months)):
			$months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
		endif;
		$list = explode("-",$field);
		$list[2] = (int)$list[2];
		$field = implode("-",$list);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 г.";
		return preg_replace($pattern, $replacement,$field);
	}
	
	public static function monthsTime($field,$months = NULL){
		
		if(is_null($months)):
			$months = array("01"=>"января","02"=>"февраля","03"=>"марта","04"=>"апреля","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"августа","09"=>"сентября","10"=>"октября","11"=>"ноября","12"=>"декабря");
		endif;
		$list = explode("-",$field);
		$list[2] = (int)$list[2];
		$time = substr($field,11);
		$field = implode("-",$list).' '.$time;
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 в \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
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
}