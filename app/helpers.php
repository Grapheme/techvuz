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

