<?php

class AccountGroupsController extends \BaseController {

    public function __construct(){

    }

    public function organization(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_DASHBOARD.title'),
            'page_description'=> Lang::get('seo.COMPANY_DASHBOARD.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_DASHBOARD.keywords')
        );
        return $page_data;
    }

    public static function listener(){
        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_DASHBOARD.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_DASHBOARD.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_DASHBOARD.keywords')
        );
        return $page_data;
    }

    public static function individual(){

        return array('individual');
    }

    public static function validActiveUserAccount($account = NULL){

        if (is_null($account)):
            $account = Auth::user();
        endif;
        if($account->active == 0):
            return array('status'=>FALSE,'code'=>0,'message'=>Lang::get('interface.ACCOUNT_STATUS.blocked'));
        elseif ($account->active == 1):
            return array('status'=>TRUE,'code'=>1,'message'=>Lang::get('interface.ACCOUNT_STATUS.active'));
        elseif ($account->active == 2 && $account->code_life > time()):
            return array(
                'status'=>FALSE,
                'code'=>2,
                'message'=>Lang::get('interface.ACCOUNT_STATUS.not_active')
            );
        elseif ($account->active == 2 && $account->code_life < time()):
            return array(
                'status'=>FALSE,
                'code'=>3,
                'message'=>Lang::get('interface.ACCOUNT_STATUS.blocked_approve')
            );
        else:
            return FALSE;
        endif;
    }
}
