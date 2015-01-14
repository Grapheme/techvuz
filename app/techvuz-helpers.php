<?php

function isOrganizationORListeners(){

    if (Auth::check()):
        if(in_array(Auth::user()->group()->pluck('name'),array('organization','listener','individual'))):
            return TRUE;
        endif;
    endif;
    return FALSE;
}

function isOrganizationORIndividual(){

    if (Auth::check()):
        if(in_array(Auth::user()->group()->pluck('name'),array('organization','individual'))):
            return TRUE;
        endif;
    endif;
    return FALSE;
}

function isOrganization(){

    if (Auth::check() && Auth::user()->group()->pluck('name') == 'organization'):
        return TRUE;
    endif;
    return FALSE;
}

function isIndividual(){

    if (Auth::check() && Auth::user()->group()->pluck('name') == 'individual'):
        return TRUE;
    endif;
    return FALSE;
}

function isCompanyListener(){

    if (Auth::check() && Auth::user()->group()->pluck('name') == 'listener'):
        return TRUE;
    endif;
    return FALSE;
}

function isModerator(){
    if (Auth::check() && Auth::user()->group()->pluck('name') == 'moderator'):
        return TRUE;
    endif;
    return FALSE;
}

function getCourseStudyProgress($listenerCourse = NULL){
    $progress = 0;
    if (!is_null($listenerCourse) && is_object($listenerCourse)):
        if($listenerCourse->start_status == 1):
            $progress++;
        endif;
        $success_test_percent = Config::get('site.success_test_percent') ? Config::get('site.success_test_percent') : 70;
        if(OrdersListenersTests::where('order_listeners_id',$listenerCourse->id)->where('result_attempt','>=',$success_test_percent)->exists()):
            $progress++;
        endif;
        if($listenerCourse->over_status):
            $progress++;
        endif;
    endif;
    return $progress;
}

function returnZipDownloadHeaders($FilePath){

    return array(
        'Pragma: public',
        'Expires: 0',
        'Cache-Control: must-revalidate, post-check=0, pre-check=0',
        'Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime($FilePath)).' GMT',
        'Cache-Control: private',
        'Content-Type: application/zip',
        'Content-Disposition: attachment; filename="'.basename($FilePath).'"',
        'Content-Transfer-Encoding: binary',
        'Content-Length: '.filesize($FilePath),
        'Connection: close'
    );
}

function calculateDiscount($discounts,$price = NULL,$showResult = TRUE){

    if (is_array($discounts) && !empty($discounts)):
        $discount_percent = max($discounts);
        if($discount_percent > 0):
            if (is_null($price)):
                return $discount_percent;
            else:
                return $price - ($price*round($discount_percent/100,2));
            endif;
        elseif($showResult):
            if (is_null($price)):
                return FALSE;
            else:
                return $price;
            endif;
        endif;
    endif;
    return FALSE;
}

function getAccountDiscount(){

    if(isOrganization()):
        return User_organization::where('id',Auth::user()->id)->pluck('discount');
    elseif(isIndividual()):
        return User_individual::where('id',Auth::user()->id)->pluck('discount');
    else:
        return 0;
    endif;
}

function getGlobalDiscount(){

    return Dictionary::valueBySlugs('properties-site','global-discount-percent',TRUE)->property;
}

function coursesCountDiscount($listeners = NULL){

    $CountListeners = 0;
    if (is_null($listeners)):
        foreach(getJsonCookieData('ordering','values') as $course):
            $CountListeners += count($course);
        endforeach;
    else:
        foreach($listeners as $course):
            $CountListeners += count($course);
        endforeach;
    endif;
    $countProperty = Dictionary::valueBySlugs('properties-site','count-by-course-discount',TRUE)->property;
    if ($countProperty && $CountListeners >= $countProperty):
        return Dictionary::valueBySlugs('properties-site','count-by-course-discount-percent',TRUE)->property;
    endif;
    return 0;
}

function getOrderNumber($order){

    if(is_object($order)):
        return str_pad($order->number,3,'0',STR_PAD_LEFT).'-'.$order->created_at->format('y');
    elseif(is_array($order)):
        return str_pad($order['number'],3,'0',STR_PAD_LEFT).'-'.date('y',strtotime($order['created_at']));
    endif;
}

function getShortOrderNumber($order){

    if(is_object($order)):
        return str_pad($order->number,3,'0',STR_PAD_LEFT);
    elseif(is_array($order)):
        return str_pad($order['number'],3,'0',STR_PAD_LEFT);
    endif;
}

function count2str($num) {
    $nul='ноль';
    $ten=array(
        array('','одного','двух','трёх','четырех','пятерых','шестерых','семерых', 'восьмерых','девятерых'),
        array('','одного','двух','трёх','четырех','пятерых','шестерых','семерых', 'восьмерых','девятерых'),
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
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

/****************************************************************************/
/*********************** ДЛЯ ДОКУМЕНТОВ *************************************/
/****************************************************************************/

function document_date_time($date_time = ''){
    if (!is_null($date_time) && $date_time != '0000-00-00 00:00:00'):
        return myDateTime::SwapDotDateWithTime($date_time);
    else:
        return '__________';
    endif;

}

function document_date($date_time = ''){

    if (!is_null($date_time) && $date_time != '0000-00-00 00:00:00'):
        return myDateTime::SwapDotDateWithOutTime($date_time);
    else:
        return '__________';
    endif;
}

function document_date_now(){

    return date("d.m.Y");
}

function document_date_time_now(){

    return date("d.m.Y H:i");
}