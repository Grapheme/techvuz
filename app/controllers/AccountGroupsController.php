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

    public function listener(){

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
        $lostDays = myDateTime::getDiffDate(date("Y-m-d H:i:s",time()),date("Y-m-d H:i:s",$account->code_life));
        if($account->active == 0):
            return array('status'=>FALSE,'code'=>0,'days'=>$lostDays,'message'=>Lang::get('interface.ACCOUNT_STATUS.blocked'));
        elseif ($account->active == 1):
            return array('status'=>TRUE,'code'=>1,'days'=>$lostDays,'message'=>Lang::get('interface.ACCOUNT_STATUS.active'));
        elseif ($account->active == 2 && $account->code_life > time() && $lostDays >= 4):
            return array(
                'status'=>FALSE,
                'code'=>2,
                'days' => $lostDays,
                'message'=>Lang::get('interface.ACCOUNT_STATUS.not_active')
            );
        elseif ($account->active == 2 && $account->code_life < time()):
            return array(
                'status'=>FALSE,
                'code'=>3,
                'days' => $lostDays,
                'message'=>Lang::get('interface.ACCOUNT_STATUS.blocked_approve')
            );
        elseif ($account->active == 2 && $account->code_life > time() && $lostDays < 3):
            return array(
                'status'=>FALSE,
                'code'=>4,
                'days' => $lostDays,
                'message'=>Lang::get('interface.ACCOUNT_STATUS.not_active_few_day').' '.$lostDays.' '.Lang::choice('день|дня|дней', $lostDays).'.'
            );
        else:
            return FALSE;
        endif;
    }
}
