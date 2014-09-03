<?php

################################################################################
#### Базовый класс для генерации корректных URL-адресов в случае использования мультиязычности.
#### Добавляет префикс текущей языковой локали в ссылки.
################################################################################
## link::i18n("/") => "/" - в случае, если язык дефолтный
## link::i18n("/") => "/de" - в случае, если язык НЕ дефолтный
## link::i18n("/about") => "/ru/about" - добавление префикса языка в любом случае, если не главная страница
################################################################################

class link {
    
    
	public static function to($link = NULL){
        return url($link);
    }
    
	public static function i18n($link = NULL){

        ## Первый символ ссылки - слэш /
  		if(!is_null($link) && $link != "/" && mb_substr($link, 0, 1) != '/')
			$link = '/'.$link;

        ## Берем локаль из сесссии (если она там есть)
        $locale_session = Session::get('locale');
        #echo "session locale = " . $locale_session; die;
        ## Берем локаль из конфига (уж там-то она точно должна быть)
		$locale = Config::get("app.locale");
        ## Если локали в конфиге и в сессии не совпадают - то устанавливаем текущей локаль из сессии
		if ($locale_session != '' && $locale_session != $locale)
		    $locale = $locale_session;
        else
            ## Сохраняем текущую локаль в сессию
            Session::put('locale', $locale);

		if(!is_null($locale)) {
			$string = $locale.(mb_substr($link,0,1)!="/"?"/":"").$link;
			if(Request::secure()):
				return secure_url($string);
			else:
				return url($string);
			endif;
		} else {
			return url($link);
		}
	}


	public static function auth($link = NULL){


		if(!is_null($link) && $link != "/" && mb_substr($link, 0, 1) != '/')
			$link = '/'.$link;

        #$_locale = Session::get('locale');

        #Helper::dd( AuthAccount::getStartPage() );

		if(Auth::check()) {
            $return = self::createLink(AuthAccount::getStartPage().$link);
            #Helper::dd($return);
            return $return;
		} else {
			return url($link);
		}
	}

	public static function auth2($link = NULL){

		if(!is_null($link) && $link != "/" && mb_substr($link, 0, 1) != '/')
			$link = '/'.$link;

        #$_locale = Session::get('locale');
        #Helper::dd( AuthAccount::getStartPage() );

		if(Auth::check()) {
            #return self::createLink(AuthAccount::getStartPage().$link);
            return '/'.AuthAccount::getStartPage().$link;
		} else {
			return url($link);
		}
	}

	public static function createLink($link = NULL){

		if(!is_null($link) && $link != "/" && mb_substr($link, 0, 1) != '/')
			$link = '/'.$link;

        #Helper::dd($link);

        #return url($link);
        return $link;

		$_locale = Session::get('locale');

		if(!is_null($_locale)):
			$string = $_locale.$link;
			if(Request::secure()):
				return secure_url($string);
			else:
				return url($string);
			endif;
		else:
			return url($link);
		endif;
	}

	public static function path($link){

		if(ssl::is()):
			return secure_asset($link);
		else:
			return asset($link);
		endif;
	}

	public static function fix($link) {

        #Helper::d($link);
        $result = $link; 

        preg_match("~^https?://([^/]+?)/~is", $link, $matches);
        if (!@$matches[1])
            return $result;

        #Helper::dd($matches);

        $result = str_replace($matches[0].$matches[0], $matches[0], $link);
        return $result;

    }

	public static function previous() {
        return self::fix(URL::previous());
    }
}
