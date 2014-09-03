<?php

class AuthAccount {
	
	public static function getStartPage($url = NULL){
		
		$StartPage = '';
		if(Auth::check()):
			#$StartPage = Auth::user()->groups()->first()->dashboard;
#Helper::dd(Auth::user()->group);
            $group = Auth::user()->group;
			#$StartPage = $group->start_url ? $group->start_url : $group->dashboard;
			$StartPage = $group->dashboard;
		endif;
        #Helper::dd($StartPage);
		if(!is_null($url)):
			return $StartPage.'/'.$url;
		else:
			return $StartPage;
		endif;
	}
	
	public static function getGroupID(){
		
		if(Auth::check()):
			return Auth::user()->group->id;
		else:
			return FALSE;
		endif;
	}

	public static function getGroupStartUrl(){

		$StartUrl = '';
		if(Auth::check()):
            $group = Auth::user()->group;
			$StartUrl = $group->start_url ? $group->start_url : $group->dashboard;
		endif;
        return $StartUrl;
	}
	
    /**
     * @TODO Выпилить и не использовать
     */
	public static function isAdminLoggined(){
		
		if(self::getGroupID() == 1):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
    /**
     * @TODO Выпилить и не использовать
     */
	public static function isUserLoggined(){
		
		if(self::getGroupID() == 2):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
}
