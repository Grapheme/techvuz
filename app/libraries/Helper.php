<?php

class Helper {

	/*
	| Функция возвращает 2х-мерный массив который формируется из строки.
	| Строка сперва разбивается по запятой, потом по пробелам.
	| Используется пока для разбора строки сортировки model::orderBy() в ShortCodes
	*/
    ## from BaseController
	public static function stringToArray($string){

		$ordering = array();
		if(!empty($string)):
			if($order_by = explode(',',$string)):
				foreach($order_by as $index => $order):
					if($single_orders = explode(' ',$order)):
						foreach($single_orders as $single_order):
							$ordering[$index][] = strtolower($single_order);
						endforeach;
					endif;
				endforeach;
			endif;
		endif;
		return $ordering;
	}
    
    public static function d($array) {
        echo "<pre style='text-align:left'>" . print_r($array, 1) . "</pre>";
    }

    public static function dd($array) {
        self::d($array);
        die;
    }

    public static function d_($array) {
        return false;
    }

    public static function dd_($array) {
        return false;
    }

    public static function ta($object) {
        self::d($object->toArray());
    }

    public static function tad($object) {
        self::dd(is_object($object) ? $object->toArray() : $object);
    }

    public static function ta_($array) {
        return false;
    }

    public static function tad_($array) {
        return false;
    }

    public static function layout($file = '') {
        $layout = Config::get('app.template');
        #Helper::dd(Config::get('app'));
        if (!$layout)
            $layout = 'default';
        if (Request::ajax() && View::exists("templates." . $layout . ".ajax"))
            $file = 'ajax';
        #Helper::dd("templates." . $layout . ($file ? '.'.$file : ''));
        return "templates." . $layout . ($file ? '.'.$file : '');
    }

    public static function acclayout($file = '') {
        $layout = AuthAccount::getStartPage();
        if (!$layout)
            $layout = 'default';
        if (Request::ajax() && View::exists("templates." . $layout . ".ajax"))
            $file = 'ajax';
        #Helper::dd( (("templates." . $layout . ".ajax")) );
        return "templates." . $layout . ($file ? '.'.$file : '');
    }

    public static function inclayout($file) {
        if (!$file)
            return false;

        $layout = Config::get('app.template');

        if (!$layout)
            $layout = 'default';

        $full = base_path() . "/app/views/templates/" . $layout . '/' . $file;

        if(!file_exists($full))
            $full .= ".blade.php";

        #if (!file_exists($full))
        #    return false;

        return $full;
    }

    public static function rdate($param = "j M Y", $time=0, $lower = true) {
        if (!is_int($time) && !is_numeric($time))
            $time = strtotime($time);
    	if (intval($time)==0)
            $time=time();
    	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
    	if(strpos($param,'M')===false)
            return date($param, $time);
    	else {
            $month = $MonthNames[date('n', $time)-1];
            if ($lower)
                $month = mb_strtolower($month);
            return date(str_replace('M', $month, $param), $time);
        }
    }

    public static function preview($text, $count = 10, $threedots = true) {

        $words = array();
        $temp = explode(" ", strip_tags($text));

        foreach ($temp as $t => $tmp) {
            #$tmp = trim($tmp, ".,?!-+/");
            if (!$tmp)
                continue;
            $words[] = $tmp;
            if (count($words) >= $count)
                break;
        }

        $preview = trim(implode(" ", $words));

        if (mb_strlen($preview) < mb_strlen(trim(strip_tags($text))) && $threedots)
            $preview .= "...";

        return $preview;
    }

    public static function firstletter($text, $dot = true) {

        return trim($text) ? mb_substr(trim($text), 0, 1) . ($dot ? '.' : '') : false;
    }


    public static function arrayForSelect($object, $key = 'id', $val = 'name') {

        if (!isset($object) || (!is_object($object) && !is_array($object)))
            return false;

        #Helper::d($object); return false;

        $array = array();
        #$array[] = "Выберите...";
        foreach ($object as $o => $obj) {
            $array[@$obj->$key] = @$obj->$val;
        }

        #Helper::d($array); #return false;

        return $array;
    }

    public static function valuesFromDic($object, $key = 'id') {

        if (!isset($object) || (!is_object($object) && !is_array($object)))
            return false;

        #Helper::d($object); return false;

        $array = array();
        foreach ($object as $o => $obj) {
            $array[] = is_object($obj) ? @$obj->$key : @$obj[$key];
        }

        #Helper::d($array);

        return $array;
    }

    /**
     * Изымает значение из массива по ключу, возвращая это значение. Работает по аналогии array_pop()
     * @param $array
     * @param $key
     * @return mixed
     */
    public static function withdraw(&$array, $key) {
        $val = @$array[$key];
        unset($array[$key]);
        return $val;
    }

    public static function classInfo($classname) {
        echo "<pre>";
        Reflection::export(new ReflectionClass($classname));
        echo "</pre>";
    }

    public static function nl2br($text) {
        $text = preg_replace("~[\r\n]+~is", "\n<br/>\n", $text);
        return $text;
    }

    /**************************************************************************************/

    public static function cookie_set($name = false, $value = false, $lifetime = 86400) {
        if(is_object($value) || is_array($value))
            $value = json_encode($value);

        #Helper::dd($value);

        setcookie($name, $value, time()+$lifetime, "/");
        if ($lifetime > 0)
            $_COOKIE[$name] = $value;
    }

    public static function cookie_get($name = false) {
        #Helper::dd($_COOKIE);
        $return = @isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        $return2 = @json_decode($return, 1);
        #Helper::dd($return2);
        if (is_array($return2))
            $return = $return2;
        return $return;
    }

    public static function cookie_drop($name = false) {
        self::cookie_set($name, false, 0);
        $_COOKIE[$name] = false;
    }

    /**************************************************************************************/

    public static function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array(
            'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e', 'ё'=>'e', 'ж'=>'j', 'з'=>'z',
            'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r',
            'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'х'=>'h', 'ц'=>'c', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'sch',
            'ы'=>'y', 'э'=>'e', 'ю'=>'yu', 'я'=>'ya', 'ъ'=>'', 'ь'=>''
        ));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }

    public static function hiddenGetValues() {
        if (@!count($_GET))
            return false;
        $return = '';
        foreach ($_GET as $key => $value) {
            if (!$key || !$value)
                continue;
            $return .= "<input type='hidden' name='{$key}' value='{$value}' />";
        }
        return $return;
    }


    public static function routes() {
        $routes = Route::getRoutes();
        foreach($routes as $route) {
            echo URL::to($route->getPath()) . " <br/>\n";
        }
    }


    public static function drawmenu($menus = false) {

        if (!$menus || !is_array($menus) || !count($menus))
            return false;

        $return = '';
        $current_url = (Request::secure() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        #Helper::d($_SERVER);

        $return .= <<<HTML
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="margin-bottom-25 margin-top-10 ">
HTML;

        foreach ($menus as $menu) {
            $child_exists = (isset($menu['child']) && is_array($menu['child']) && count($menu['child']));

            if ($child_exists)
                $return .= '<div class="btn-group">';

            if (isset($menu['raw']) && $menu['raw'] != '') {

                $return .= $menu['raw'];

            } elseif (isset($menu['link'])) {

                $current = ($current_url == $menu['link']);

                $return .= "\n<!--\n" . $_SERVER['REQUEST_URI'] . "\n" . $menu['link'] . "\n-->\n";

                $return .= '<a class="' . @$menu['class'] . '" href="' . $menu['link'] . '">'
                    . ($current ? '<i class="fa fa-check"></i> ' : '')
                    . @$menu['title'] . '</a> ';

                if ($child_exists) {
                    $return .=  '<a class="btn btn-default dropdown-toggle ' . @$menu['class'] . '" dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
    <span class="caret"></span>
</a>
<ul class="dropdown-menu text-left">';

                    foreach ($menu['child'] as $child)
                        $return .=  '<li><a class="' . @$child['class'] . '" href="' . @$child['link'] . '">' . @$child['title'] . '</a></li> ';

                    $return .=  '</ul> ';
                }

            }

            if ($child_exists)
                $return .=  "</div> ";

        }

        $return .=  <<<HTML
        </div>
    </div>
</div>
HTML;

        return $return;

    }

    ##
    ## Uses in Dictionaries module (DicVal additional fields)
    ##
    public static function formField($name, $array, $value = false) {

        if (!@$array || !is_array($array) || !@$name)
            return false;

        if (isset($array['content']))
            return $array['content'];

        $return = '';
        #$name = $array['name'];
        #if ($name_group != '')
        #    $name = $name_group . '[' . $name . ']';

        $value = $value ? $value : @$array['default'];

        #Helper::d($value);

        if (@is_callable($array['value_modifier'])) {
            $value = $array['value_modifier']($value);
        }
        #Helper::d($value);

        $others = array();
        $others_array = array();
        if (@count($array['others'])) {
            foreach ($array['others'] as $o => $other) {
                $others[] = $o . '="' . $other . '"';
                $others_array[$o] = $other;
            }
        }
        $others = ' ' . implode(' ', $others);
        #$others_string = self::arrayToAttributes($others_array);
        switch (@$array['type']) {
            case 'text':
                $return = Form::text($name, $value, $others_array);
                break;
            case 'textarea':
                $return = Form::textarea($name, $value, $others_array);
                break;
            case 'textarea_redactor':
                $others_array['class'] = trim(@$others_array['class'] . ' redactor redactor_450');
                $return = Form::textarea($name, $value, $others_array);
                break;
            case 'image':
                $return = ExtForm::image($name, $value);
                break;
            case 'gallery':
                $return = ExtForm::gallery($name, $value);
                break;
            case 'date':
                $others_array['class'] = trim(@$others_array['class'] . ' datepicker');
                $return = Form::text($name, $value, $others_array);
                break;
            case 'upload':
                $others_array['class'] = trim(@$others_array['class'] . ' file_upload');
                $return = ExtForm::upload($name, $value, $others_array);
                break;
            case 'video':
                $return = ExtForm::video($name, $value, $others_array);
                break;
        }
        return $return;
    }

    public static function arrayToAttributes($array) {
        if (!@is_array($array) || !@count($array))
            return false;

        $line = '';
        foreach ($array as $key => $value) {
            if (is_string($key) && (is_string($value) || is_int($value)))
                $line = $key . '="' . $value . '" ';
        }
        $line = trim($line);
        return $line;
    }

    public static function arrayFieldToKey(&$array, $field = 'slug') {
        #$return = $this;
        #Helper::tad($array);
        if (@count($array)) {
            $temp = array();
            foreach ($array as $key => $val) {
                #Helper::dd($val);
                $array[$val->$field] = $val;
                unset($array[$key]);
            }
        }
        return $array;
    }

    /*
     * Make menu for changing language
     * @param string $view_locale Show locale signature (RU, EN) or locale name (Русский, English)
     * @param array $tpl Template of the menu, array with keys 'block', 'link', 'current' (see this method source for help)
     * @author Alexander Zelensky
     * @return string HTML-markup menu
     */
    public static function changeLocaleMenu($view_locale = 'sign', $tpl = false) {
        $locale = Config::get('app.locale');
        $default_locale = Config::get('app.default_locale');
        $locales = Config::get('app.locales');
        #Helper::d($locales);

        $tpl_default = array(
            'block' => '<ul class="lang-ul">%links%</ul>',
            'link' => '<li class="lang-li"><a href="%link%">%locale_sign%</a></li>',
            'current' => '<li class="lang-li"><span class="lang-li-current">%locale_sign%</span></li>',
        );
        $tpl = (array)$tpl + $tpl_default;
        if (!@$tpl['current'])
            $tpl['current'] = $tpl['link'];

        $url = Request::path();
        #if ($url != '/') {
            preg_match("~^/?([^/]+)(.*?)$~is", $url, $matches);
            #Helper::d($matches);
            if (@$locales[$matches[1]])
                $url = $matches[2];
        #}
        $url = preg_replace("~^/~is", '', $url);
        #Helper::dd($url);

        $links = array();

        $links[] = strtr($tpl['current'], array(
            '%link%' => URL::to( ( $url || $locale != $default_locale ? $locale.'/' : '') . $url),
            '%locale_sign%' => ($view_locale == 'sign' ? $locale : @$locales[$locale]),
        ));

        foreach($locales as $locale_sign => $locale_name) {

            if ($locale_sign == $locale)
                continue;

            $locale_link = URL::to( ( $url || $locale_sign != $default_locale ? $locale_sign.'/' : '') . $url);

            $links[] = strtr($tpl['link'], array(
                    '%link%' => $locale_link,
                    '%locale_sign%' => ($view_locale == 'sign' ? $locale_sign : $locale_name),
                )
            );
        }

        $return = strtr($tpl['block'], array('%links%' => implode('', $links)));

        return $return;
    }

    public static function clearModuleLink($path) {

        $return = $path;

        $start = AuthAccount::getStartPage();
        $auth_acc_pos = mb_strpos($return, $start, 7);

        if ($auth_acc_pos) {
            $return = preg_replace("~.+?" . $start . "/?~is", '', $path);
        }

        #Helper::dd(AuthAccount::getStartPage() . ' = ' . $auth_acc_pos . ' = ' . $return);

        return $return;
    }

}

