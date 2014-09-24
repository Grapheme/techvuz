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