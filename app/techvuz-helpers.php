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

function getCourseStudyProgress($listenerCourse = NULL){

    $progress = 0;
    if (!is_null($listenerCourse) && is_object($listenerCourse)):
        if($listenerCourse->start_status == 1):
            $progress = 1;
        endif;
    endif;
    return $progress;
}

