<?php

class AccountGroupsController extends \BaseController {

    public function __construct(){

    }

    public static function organization(){

        $page_data['active_status'] = self::validActiveUserAccount();
        return $page_data;
    }

    public static function listener(){

        return array('listener');
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
