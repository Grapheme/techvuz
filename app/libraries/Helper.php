<?php

class Helper {

    /*
    | Функция возвращает 2х-мерный массив который формируется из строки.
    | Строка сперва разбивается по запятой, потом по пробелам.
    | Используется пока для разбора строки сортировки model::orderBy() в ShortCodes
    */
    ## from BaseController
    public static function stringToArray($string) {

        $ordering = array();
        if (!empty($string)):
            if ($order_by = explode(',', $string)):
                foreach ($order_by as $index => $order):
                    if ($single_orders = explode(' ', $order)):
                        foreach ($single_orders as $single_order):
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
        $return = $object;
        if (is_object($object)) {
            $return = $object->toArray();
        } elseif (is_array($object)) {
            foreach ($object as $o => $obj) {
                $return[$o] = is_object($obj) ? $obj->toArray() : $obj;
            }
        }
        self::d($return);
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
        if (!$layout) {
            $layout = 'default';
        }
        if (Request::ajax() && View::exists("templates." . $layout . ".ajax")) {
            $file = 'ajax';
        }
        #Helper::dd("templates." . $layout . ($file ? '.'.$file : ''));
        return "templates." . $layout . ($file ? '.' . $file : '');
    }

    public static function acclayout($file = '') {
        $layout = AuthAccount::getStartPage();
        if (!$layout) {
            $layout = 'default';
        }
        if (Request::ajax() && View::exists("templates." . $layout . ".ajax")) {
            $file = 'ajax';
        }
        #Helper::dd( (("templates." . $layout . ".ajax")) );
        return "templates." . $layout . ($file ? '.' . $file : '');
    }

    public static function theme_dir($file = '') {

        $layout = Config::get('app.template');
        if (!$layout)
            $layout = 'default';

        $full = base_path("app/views/templates/" . $layout . ( $file ? '/'.$file : ''));
        return $full;
    }

    public static function inclayout($file) {

        $layout = Config::get('app.template');

        if (!$layout) {
            $layout = 'default';
        }

        $full = base_path("/app/views/templates/" . $layout . '/' . $file);

        if ($file != '' && !file_exists($full)) {
            $full .= ".blade.php";
        }

        if (!file_exists($full))
            return false;

        return $full;
    }

    public static function rdate($param = "j M Y", $time = 0, $lower = true) {
        if (!is_int($time) && !is_numeric($time)) {
            $time = strtotime($time);
        }
        if (intval($time) == 0) {
            $time = time();
        }
        $MonthNames = array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
        if (strpos($param, 'M') === false) {
            return date($param, $time);
        } else {
            $month = $MonthNames[date('n', $time) - 1];
            if ($lower) {
                $month = mb_strtolower($month);
            }
            return date(str_replace('M', $month, $param), $time);
        }
    }

    public static function preview($text, $count = 10, $threedots = true) {

        $words = array();
        $temp = explode(" ", strip_tags($text));

        foreach ($temp as $t => $tmp) {
            #$tmp = trim($tmp, ".,?!-+/");
            if (!$tmp) {
                continue;
            }
            $words[] = $tmp;
            if (count($words) >= $count) {
                break;
            }
        }

        $preview = trim(implode(" ", $words));

        if (mb_strlen($preview) < mb_strlen(trim(strip_tags($text))) && $threedots) {
            $preview .= "...";
        }

        return $preview;
    }

    public static function firstletter($text, $dot = true) {

        return trim($text) ? mb_substr(trim($text), 0, 1) . ($dot ? '.' : '') : false;
    }


    public static function arrayForSelect($object, $key = 'id', $val = 'name') {

        if (!isset($object) || (!is_object($object) && !is_array($object))) {
            return false;
        }

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

        if (!isset($object) || (!is_object($object) && !is_array($object))) {
            return false;
        }

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
    public static function withdraw(&$array, $key = '') {
        $val = @$array[$key];
        unset($array[$key]);
        return $val;
    }

    public static function withdraws(&$array, $keys = array()) {
        $vals = array();
        foreach ($keys as $key) {
            $vals[$key] = @$array[$key];
            unset($array[$key]);
        }
        return $vals;
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
        if (is_object($value) || is_array($value)) {
            $value = json_encode($value);
        }

        #Helper::dd($value);

        setcookie($name, $value, time() + $lifetime, "/");
        if ($lifetime > 0) {
            $_COOKIE[$name] = $value;
        }
    }

    public static function cookie_get($name = false) {
        #Helper::dd($_COOKIE);
        $return = @isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
        $return2 = @json_decode($return, 1);
        #Helper::dd($return2);
        if (is_array($return2)) {
            $return = $return2;
        }
        return $return;
    }

    public static function cookie_drop($name = false) {
        self::cookie_set($name, false, 0);
        $_COOKIE[$name] = false;
    }

    /**************************************************************************************/

    public static function translit($s, $lower = true, $space = '-') {
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace('/ +/', ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        if ($lower)
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array(
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => '',

            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'E', 'Ж' => 'J', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH',
            'Ы' => 'Y', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'Ъ' => '', 'Ь' => '',
        ));
        $s = preg_replace("/[^0-9A-Za-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", $space, $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }

    public static function eng2rus($s, $lower = true, $space = '-') {
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace('/ +/', ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        if ($lower)
            $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = $s . ' ';
        $s = strtr($s, array(
            'y ' => 'и', 'e ' => 'и', 'ch' => 'ч',

            'a' => 'а', 'b' => 'б', 'c' => 'ц', 'd' => 'д', 'e' => 'е', 'f' => 'ф', 'g' => 'г', 'h' => 'х', 'i' => 'и', 'j' => 'ж',
            'k' => 'к', 'l' => 'л', 'm' => 'м', 'n' => 'н', 'o' => 'о', 'p' => 'п', 'q' => 'к', 'r' => 'р', 's' => 'с', 't' => 'т',
            'u' => 'ю', 'v' => 'в', 'w' => 'в', 'x' => 'кс', 'y' => 'и', 'z' => 'з',
        ));
        #$s = preg_replace("/[^0-9A-Za-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = trim($s);
        $s = str_replace(" ", $space, $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }

    /**************************************************************************************/

    public static function hiddenGetValues() {
        if (@!count($_GET)) {
            return false;
        }
        $return = '';
        foreach ($_GET as $key => $value) {
            if (!$key || !$value) {
                continue;
            }
            $return .= "<input type='hidden' name='{$key}' value='{$value}' />";
        }
        return $return;
    }


    public static function routes() {
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            echo URL::to($route->getPath()) . " <br/>\n";
        }
    }


    public static function drawmenu($menus = false) {

        if (!$menus || !is_array($menus) || !count($menus)) {
            return false;
        }

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

            if ($child_exists) {
                $return .= '<div class="btn-group margin-bottom-5">';
            }

            if (isset($menu['raw']) && $menu['raw'] != '') {

                $return .= $menu['raw'];

            } elseif (isset($menu['link'])) {

                $current = ($current_url == $menu['link']);

                #$return .= "\n<!--\n" . $_SERVER['REQUEST_URI'] . "\n" . $menu['link'] . "\n-->\n";

                $additional = isset($menu['others']) ? self::arrayToAttributes($menu['others']) : '';

                $return .= '<a class="' . @$menu['class'] . ($child_exists ? '' : ' margin-bottom-5') . '" href="' . $menu['link'] . '" ' . $additional . '>'
                        . ($current ? '<i class="fa fa-check"></i> ' : '')
                        . @$menu['title'] . '</a> ';

                if ($child_exists) {
                    $return .= '<a class="btn btn-default dropdown-toggle ' . @$menu['class'] . '" dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
    <span class="caret"></span>
</a>
<ul class="dropdown-menu text-left">';

                    foreach ($menu['child'] as $child) {
                        $return .= '<li><a class="' . @$child['class'] . '" href="' . @$child['link'] . '">' . @$child['title'] . '</a></li> ';
                    }

                    $return .= '</ul> ';
                }

            }

            if ($child_exists) {
                $return .= "</div> ";
            }

        }

        $return .= <<<HTML
        </div>
    </div>
</div>
HTML;

        return $return;

    }

    public static function buildExcerpts($docs = false, $index = '*', $words = false, $opts = false) {

        if (!$docs || !$words)
            return false;

        $opts_default = array(
            'before_match' => '<b>',
            'after_match' => '</b>',
            'chunk_separator' => '...',
            'limit' => 256,
            'around' => 5,
            'exact_phrase' => FALSE,
            'single_passage' => FALSE
        );
        $opts = (array)$opts + $opts_default;
        #Helper::dd($opts);

        /**
         * VENDOR
         * scalia/sphinxsearch
         */
        $host = \Config::get('sphinxsearch::host');
        $port = \Config::get('sphinxsearch::port');
        /**
         * VENDOR
         * gigablah/sphinxphp
         */
        $sphinx = new \Sphinx\SphinxClient;
        $sphinx->setServer($host, $port);
        $results = $sphinx->buildExcerpts($docs, $index, $words, $opts);
        ##Helper::d($results);
        
        /**
         * Костыль-с...
         */
        $n = 0;
        $temp = array();
        foreach ($docs as $d => $doc)
            $temp[$d] = $results[$n++];
        unset($sphinx);
        return $temp;
    }


    public static function multiSpace($a) {
        return preg_replace('~\s\s+~is', " ", $a);
    }

    ##
    ## Uses in Dictionaries module (DicVal additional fields)
    ## $element - current DicVal model
    ##
    public static function formField($name, $array, $value = false, $element = false) {

        if (!@$array || !is_array($array) || !@$name) {
            return false;
        }

        if (isset($array['content'])) {
            return $array['content'];
        }

        #Helper::d($array);

        $return = '';
        #$name = $array['name'];
        #if ($name_group != '')
        #    $name = $name_group . '[' . $name . ']';

        #var_dump($value);

        $value = (isset($value) && $value !== NULL) ? $value : @$array['default'];

        #echo (int)(isset($value) && $value !== NULL);
        #var_dump($value);

        if (is_object($element) && $element->id) {
            $element = $element->extract();
        }

        /*
        foreach ($element['fields'] as $f => $field) {
            $element['fields'][$field['key']] = $field;
            unset($element['fields'][$f]);
        }
        */

        #Helper::ta($element);

        if (@is_callable($array['value_modifier'])) {
            $value = $array['value_modifier']($value, $element);
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
                $others_array['class'] = trim(@$others_array['class'] . ' redactor redactor_150');
                $return = Form::textarea($name, $value, $others_array);
                break;
            case 'image':
                $return = ExtForm::image($name, $value, @$array['params']);
                break;
            case 'gallery':
                $return = ExtForm::gallery($name, $value, @$array['params']);
                break;
            case 'date':
                $others_array['class'] = trim(@$others_array['class'] . ' datepicker');
                $return = Form::text($name, $value, $others_array);
                break;
            case 'upload':
                $others_array['class'] = trim(@$others_array['class'] . ' file_upload');
                #Helper::dd($others_array);
                $return = ExtForm::upload($name, $value, $others_array);
                break;
            case 'video':
                $return = ExtForm::video($name, $value, $others_array);
                break;
            case 'select':
                #Helper::d($array);
                #Helper::dd($value);
                $values = $array['values'];
                $return = Form::select($name, $values, $value, $others_array);
                break;
            case 'select-multiple':
                $values = $array['values'];
                $others_array['class'] = trim(@$others_array['class'] . ' select-multiple select2');
                $others_array['multiple'] = 'multiple';
                $return = Form::select($name . '[]', $values, $value, $others_array);
                break;
            case 'checkbox':
                #Helper::d($array);
                #Helper::ta($element);
                return '<label class="checkbox">'
                . Form::checkbox($name, 1, @$element->$array['_name'], $others_array)
                . '<i></i>'
                . '<span>' . $array['title'] . '</span>'
                . '</label>';
                break;
            case 'checkboxes':
                $return = '';
                $style = '';
                $col_num = 12;
                if ($array['columns'] == 2) {
                    $style = ' col col-6';
                } elseif ($array['columns'] == 3) {
                    $style = ' col col-4';
                }
                foreach ($array['values'] as $key => $val) {
                    $checked = is_array($value) && isset($value[$key]);
                    $el = '<label class="checkbox' . $style . '">' . "\n"
                        . Form::checkbox($name . '[]', $key, $checked, $others_array) . "\n"
                        . '<i></i>' . "\n"
                        . '<span>' . $val . '</span>' . "\n"
                        . '</label>' . "\n\n";
                    $return .= $el;
                }
                #Helper::d(htmlspecialchars($return));
                break;
        }
        return $return;
    }

    public static function arrayToAttributes($array) {
        if (!@is_array($array) || !@count($array)) {
            return false;
        }

        $line = '';
        foreach ($array as $key => $value) {
            if (is_string($key) && (is_string($value) || is_int($value))) {
                $line = $key . '="' . $value . '" ';
            }
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
        if (!@$tpl['current']) {
            $tpl['current'] = $tpl['link'];
        }

        $url = Request::path();
        #if ($url != '/') {
        preg_match("~^/?([^/]+)(.*?)$~is", $url, $matches);
        #Helper::d($matches);
        if (@$locales[$matches[1]]) {
            $url = $matches[2];
        }
        #}
        $url = preg_replace("~^/~is", '', $url);
        #Helper::dd($url);

        $links = array();

        $links[] = strtr($tpl['current'], array(
            '%link%' => URL::to(($url || $locale != $default_locale ? $locale . '/' : '') . $url),
            '%locale_sign%' => ($view_locale == 'sign' ? $locale : @$locales[$locale]),
        ));

        foreach ($locales as $locale_sign => $locale_name) {

            if ($locale_sign == $locale) {
                continue;
            }

            $locale_link = URL::to(($url || $locale_sign != $default_locale ? $locale_sign . '/' : '') . $url);

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
        if (!$start) {
            return $return;
        }

        $auth_acc_pos = @mb_strpos($return, $start, 7);
        if ($auth_acc_pos) {
            $return = preg_replace("~.+?" . $start . "/?~is", '', $path);
        }

        #Helper::dd(AuthAccount::getStartPage() . ' = ' . $auth_acc_pos . ' = ' . $return);

        return $return;
    }


    public static function smartFilesize($number) {

        $len = strlen($number);
        if ($len < 4) {
            return sprintf("%d b", $number);
        }
        if ($len >= 4 && $len <= 6) {
            return sprintf("%0.2f Kb", $number / 1024);
        }
        if ($len >= 7 && $len <= 9) {
            return sprintf("%0.2f Mb", $number / 1024 / 1024);
        }
        return sprintf("%0.2f Gb", $number / 1024 / 1024 / 1024);
    }


    public static function isRoute($route_name = false, $route_params = array(), $match_text = ' class="active"', $mismatch_text = '') {

        $match = true;
        $route = Route::getCurrentRoute();
        #dd($route->getAction());
        #dd($route->getPath());

        if (is_string($route_params)) {
            preg_match('~\{([^\}]+?)\}~is', $route->getPath(), $matches);
            #Helper::dd($matches);
            if (@$matches[1] != '') {
                $route_params = array($matches[1] => $route_params);
            } else {
                $route_params = array();
            }
        }

        #Helper::d($route_params);

        if (count($route_params)) {

            #Helper::d($route_params);
            $route_params = URL::get_modified_parameters($route_name, $route_params);
            #Helper::dd($route_params);

            foreach ($route_params as $key => $value) {

                #Helper::d("[" . $key . "] => " . $route->getParameter($key) . " = " . $value);

                if ($route->getParameter($key) != $value) {
                    $match = false;
                    break;
                }
            }
        }

        #Helper::d((int)$match);

        return (Route::currentRouteName() == $route_name && $match) ? $match_text : $mismatch_text;
    }

    public static function mb_ucfirst($str, $encoding = 'UTF-8') {
        $first = mb_substr($str, 0, 1, $encoding);
        $rest = mb_substr($str, 1, strlen($str), $encoding);
        return mb_strtoupper($first, $encoding) . $rest;
    }

    public static function multiArrayToAttributes($array, $name) {
        if (!is_array($array) || !count($array)) {
            return array();
        }
        $return = array();
        foreach ($array as $key => $val) {
            #Helper::d($name);
            #Helper::d($key);
            #Helper::d($val);

            if (is_array($val)) {
                $return += self::multiArrayToAttributes($val, $name . '[' . $key . ']');
            } else {
                #Helper::d('!!! ' . $name.'['.$key.']  = ' . $val);
                $return[$name . '[' . $key . ']'] = $val;
            }
        }
        return $return;
    }

    /**
     * Функция для вывода выпадающего списка в верхнем меню для фильтрации результатов
     *
     * @param $filter_name
     * @param $filter_default_text
     * @param $filter_dic_elements - array like: array('_id_of_the_dicval_' => '_name_of_the_dicval_')
     * @param $dic
     * @param bool $dicval
     * @return array
     */
    public static function getDicValMenuDropdown($filter_name, $filter_default_text, $filter_dic_elements, $dic, $dicval = false) {

        $filter = Input::get('filter.fields');
        #Helper::d($filter);
        #Helper::ta($dic);

        $dic_id = $dic->entity ? $dic->slug : $dic->id;
        $route = $dic->entity ? 'entity.index' : 'dicval.index';

        ## Get dimensional array for filtration from multidimensional array (Input::get()) #NOSQL
        $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');

        ## Main element of the drop-down menu
        if (@$filter[$filter_name]) {

            ## Get current dicval from array of the gettin' filter_dic_elements #NOSQL
            $current_dicval = @$filter_dic_elements[$filter[$filter_name]];

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            $array["filter[fields][{$filter_name}]"] = @$filter[$filter_name];
            $array = (array)$dic_id + $array;

            $parent = array(
                'link' => URL::route($route, $array),
                'title' => $current_dicval,
                'class' => 'btn btn-default',
            );
        } else {

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            unset($array["filter[fields][{$filter_name}]"]);
            $array = (array)$dic_id + $array;

            $parent = array(
                'link' => URL::route($route, $array),
                'title' => $filter_default_text,
                'class' => 'btn btn-default',
            );
        }
        ## Child elements
        $product_types = array();
        if (@$filter[$filter_name]) {

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            unset($array["filter[fields][{$filter_name}]"]);
            $array = (array)$dic_id + $array;

            $product_types[] = array(
                'link' => URL::route($route, $array),
                'title' => $filter_default_text,
                'class' => '',
            );
        }
        foreach ($filter_dic_elements as $element_id => $element_name) {

            if ($element_id == @$filter[$filter_name]) {
                continue;
            }

            ## Get all current link attributes & modify for next url generation
            $array = $current_link_attributes;
            $array["filter[fields][{$filter_name}]"] = $element_id;
            $array = (array)$dic_id + $array;

            $product_types[] = array(
                'link' => URL::route($route, $array),
                'title' => $element_name,
                'class' => '',
            );
        }
        ## Assembly
        $parent['child'] = $product_types;
        return $parent;

    }

    /**
     * Smart view of ALL Eloquent queries
     *
     * @param bool $force
     */
    public static function smartQueries($force = false) {
        foreach (DB::getQueryLog() as $query) {
            self::smartQuery($query, $force);
        }
    }

    /**
     * Smart view of single Eloquent query
     *
     * @param array $query
     * @param bool $force
     */
    public static function smartQuery($query = array(), $force = false) {

        #Helper::dd($query);

        #$data = compact('bindings', 'time', 'name');

        // Format binding data for sql insertion
        #$bindings = array();
        $bindings = $query['bindings'];
        foreach ($query['bindings'] as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } elseif (is_string($binding)) {
                $bindings[$i] = "'$binding'";
            }
        }

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query['query']);
        #Helper::dd($query);
        #Helper::dd($bindings);
        $query = vsprintf($query, $bindings);

        $changes = array(
            'select ' => "SELECT ",
            ' from' => "\nFROM",
            ' where' => "\nWHERE",
            ' inner join' => "\nINNER JOIN",
            ' left join' => "\nLEFT JOIN",
            ' right join' => "\nRIGHT JOIN",
            ' join' => "\nJOIN",
            ' on' => " ON",
            ' and' => " AND",
            ' or' => " OR",
            ' in' => " IN",
            ' group by' => "\nGROUP BY",
            ' order by' => "\nORDER BY",
            ' limit' => "\nLIMIT",
        );

        $query = strtr($query, $changes);

        if ($force) {
            Helper::d($query);
        } else {
            Log::info($query);
        }
    }

    public static function getMenu($menu_name = '') {

        $menus = Config::get('menu', array());
        if (!isset($menus[$menu_name]) || !is_callable($menus[$menu_name])) {
            return false;
        }

        $menu = $menus[$menu_name]();

        if (!@$menu['tpl']['container'] || !@$menu['tpl']['element_container'] || !@$menu['tpl']['element'] || !@is_array($menu['elements']) || !@count($menu['elements'])
        ) {
            return false;
        }

        /**
         * Menu template
         */
        $tpl_container = $menu['tpl']['container'];
        $tpl_element_container = $menu['tpl']['element_container'];
        $tpl_element = $menu['tpl']['element'];
        $active_class = $menu['active_class'];

        $fake_attributes = array('_href', '_route', '_params', '_raw', '_text');

        /**
         * Process all menu element
         */
        $links = array();
        foreach ($menu['elements'] as $e => $element) {

            /**
             * RAW - plain text of the element
             */
            if (isset($element['_raw'])) {

                $link = $element['_raw'];

            } else {

                $url = '#';
                if (isset($element['_href'])) {
                    $url = @$element['_href'];
                } elseif (isset($element['_route'])) {
                    $url = URL::route($element['_route'], @$element['_params']);
                } elseif (isset($element['_action'])) {
                    $url = URL::action($element['_action'], @$element['_params']);
                }

                $attr = $element;
                self::withdraws($attr, $fake_attributes);
                #Helper::d($attr);

                /**
                 * Check active route
                 */
                if (@$element['_route']) {
                    $active_value = self::isRoute(@$element['_route'], @$element['_params'], $active_class);

                    #Helper::d('CLASS => ' . $active_value);

                    if ($active_value) {
                        $attr['class'] = trim(@$attr['class'] . ' ' . trim($active_value));
                    }
                }

                #Helper::dd($attr);

                /**
                 * Make string line of link attributes, from array
                 */
                $attr_array = array();
                foreach ($attr as $attribute_key => $attribute_value) {
                    $attr_array[] = $attribute_key . '="' . $attribute_value . '"';
                }
                $attr_string = implode(' ', $attr_array);

                #Helper::d($attr_string);

                /**
                 * Make link
                 */
                $link = strtr($tpl_element, array('%url%' => $url, '%attr%' => $attr_string, '%text%' => @$element['_text'],));

            }

            /**
             * Make string line of link's container attributes, from array
             */
            $container_attr_string = '';
            if (isset($element['_container_attributes']) && is_array($element['_container_attributes']) && count($element['_container_attributes'])) {
                $container_attr_array = array();
                foreach ($element['_container_attributes'] as $attribute_key => $attribute_value) {
                    $container_attr_array[] = $attribute_key . '="' . $attribute_value . '"';
                }
                $container_attr_string = implode(' ', $container_attr_array);
                #Helper::d($container_attr_string);
            }

            $link = strtr($tpl_element_container, array('%attr%' => $container_attr_string != '' ? ' ' . $container_attr_string : '', '%element%' => $link,));

            $links[] = $link;
        }
        #Helper::dd($links);
        $return = strtr($tpl_container, array('%elements%' => implode('', $links),));
        #Helper::dd($return);
        return $return;
    }

    public static function getFileProperties($file) {
        $properties = array();
        $limit = 18;

        if (!file_exists($file) || !is_file($file) || !is_readable($file))
            return $properties;

        $l = 0;
        $handle = @fopen($file, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 1024)) !== false) {
                ++$l;
                /*
                Helper::d(
                    $l . " => " . $buffer
                    . ' / ' . ($l == 1 ? (int)(trim($buffer) == '<?') . '[' . trim($buffer) . ']' : '')
                    . ' / ' . ($l == 2 ? (int)(trim($buffer) == '/**') . '[' . trim($buffer) . ']' : '')
                );
                */
                if (
                    $l > $limit
                    || ($l == 1 && mb_substr($buffer, 0, 2) != '<?')
                    || ($l == 2 && mb_substr($buffer, 0, 3) != '/**')
                )
                    break;

                #Helper::d(' + ' . $buffer . ' => ' . (int)(trim($buffer) == '<?'));

                if ($l > 2) {
                    if (mb_substr($buffer, 0, 3) == ' */')
                        break;

                    $buffer = trim($buffer, " \r\n\t*");
                    $buffer = explode(':', $buffer);
                    #Helper::d($buffer);

                    $value = @trim($buffer[1]) ?: true;
                    if ($value !== true && is_string($value) && mb_strlen($value) && mb_strpos($value, '|')) {
                        $temp = explode('|', $value);
                        $value = array();
                        foreach ($temp as $tmp) {
                            $tmp = trim($tmp);
                            if (mb_strpos($tmp, '=')) {
                                $keyval = explode('=', $tmp, 2);
                                $value[trim($keyval[0])] = trim($keyval[1]);
                            } else {
                                $value[$tmp] = true;
                            }
                        }
                    }

                    $properties[@trim($buffer[0])] = $value;
                }

            }
            if (!feof($handle)) {
                #echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        }
        return $properties;
    }

    public static function getLayoutProperties($layout = false) {
         if (!$layout)
             $layout = Config::get('app.template');

        $file = base_path("/app/views/templates/" . $layout . '.blade.php');
        return self::getFileProperties($file);
    }


    public static function detect_encoding($string, $pattern_size = 50) {
        $list = array('cp1251', 'utf-8', 'ascii', '855', 'KOI8R', 'ISO-IR-111', 'CP866', 'KOI8U');
        $c = strlen($string);
        if ($c > $pattern_size) {
            $string = substr($string, floor(($c - $pattern_size) / 2), $pattern_size);
            $c = $pattern_size;
        }

        $reg1 = '/(\xE0|\xE5|\xE8|\xEE|\xF3|\xFB|\xFD|\xFE|\xFF)/i';
        $reg2 = '/(\xE1|\xE2|\xE3|\xE4|\xE6|\xE7|\xE9|\xEA|\xEB|\xEC|\xED|\xEF|\xF0|\xF1|\xF2|\xF4|\xF5|\xF6|\xF7|\xF8|\xF9|\xFA|\xFC)/i';

        $mk = 10000;
        $enc = 'ascii';
        foreach ($list as $item) {
            $sample1 = @iconv($item, 'cp1251', $string);
            $gl = @preg_match_all($reg1, $sample1, $arr);
            $sl = @preg_match_all($reg2, $sample1, $arr);
            if (!$gl || !$sl) {
                continue;
            }
            $k = abs(3 - ($sl / $gl));
            $k += $c - $gl - $sl;
            if ($k < $mk) {
                $enc = $item;
                $mk = $k;
            }
        }
        return $enc;
    }
}

