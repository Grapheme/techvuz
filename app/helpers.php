<?php

function is_image($filename){

    $is = @getimagesize($filename);
    if (!$is):
        return false;
    elseif (!in_array($is[2], array(1, 2, 3))):
        return false;
    else:
        return true;
    endif;
}

function modifyKeys($collection, $key = 'slug',$unset = false) {
    $array = array();
    if (empty($collection)):
        return NULL;
    endif;
    foreach ($collection as $c => $col):
        if (isset($col->$key)):
            $array[$col->$key] = $col;
            if ($unset):
                unset($array[$col->$key][$key]);
            endif;
        endif;
    endforeach;
    return $array;
}

function modifyArrayKeys($array, $key = 'id',$unset = false) {
    $result_array = array();
    foreach ($array as $c => $col):
        if (isset($col[$key])):
            $result_array[$col[$key]] = $col;
            if ($unset):
                unset($result_array[$col[$key]][$key]);
            endif;
        endif;
    endforeach;
    return $result_array;
}

function hasCookieData($name = null){
    if (!is_null($name)):
        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])):
            return TRUE;
        endif;
    endif;
    return FALSE;
}

function getJsonCookieData($name = null, $return = 'keys'){
    if (!is_null($name)):
        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])):
            if ($return == 'keys'):
                return array_keys(json_decode($_COOKIE[$name],TRUE));
            elseif ($return == 'values'):
                return array_values(json_decode($_COOKIE[$name],TRUE));
            elseif($return == 'values_unique'):
                $values_unique = array();
                foreach(json_decode($_COOKIE['ordering'],TRUE) as $index => $values):
                    foreach($values as $value):
                        $values_unique[] = $value;
                    endforeach;
                endforeach;
                return array_unique($values_unique);
            endif;
        endif;
    endif;
    return array();
}

function getArrayCookieStringData($name = null){
    if (!is_null($name)):
        if (isset($_COOKIE[$name]) && !empty($_COOKIE[$name])):
            return explode(',',$_COOKIE[$name]);
        endif;
    endif;
    return array();
}

function returnDownloadHeaders($document){

    return array(
        'Pragma: public',
        'Expires: 0',
        'Cache-Control: must-revalidate, post-check=0, pre-check=0',
        'Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime(public_path($document['path']))).' GMT',
        'Cache-Control: private',
        'Content-Type: '.$document['mimetype'],
        'Content-Disposition: attachment; filename="'.basename(public_path($document['path'])).'"',
        'Content-Transfer-Encoding: binary',
        'Content-Length: '.filesize(public_path($document['path'])),
        'Connection: close'
    );
}

function hasNamedRoute($routeName){

    return Route::getRoutes()->hasNamedRoute($routeName);
}

function returnRoute($routeName,$vars = [],$default = ''){
    if (hasNamedRoute($routeName)):
        return URL::route($routeName,$vars);
    else:
        return $default;
    endif;
}

function price2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}