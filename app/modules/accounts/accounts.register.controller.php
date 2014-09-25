<?php

class AccountsRegisterController extends BaseController {

    public static $name = 'registration';
    public static $group = 'accounts';
    public static $entity = 'registration';
    public static $entity_name = 'Регистрация пользователей';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'guest', 'prefix' => ''), function() use ($class) {
            Route::post('registration/ul', array('as' => 'signup-ul', 'uses' => $class.'@signupUL'));
            Route::post('registration/fl', array('as' => 'signup-fl', 'uses' => $class.'@signupFL'));
        });
    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return NULL;
    }

    public static function returnInfo() {
        return NULL;
    }

    public static function returnMenu() {
        return NULL;
    }

    /****************************************************************************/

    public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    /****************************************************************************/

    public function signupUL(){

        Helper::dd(Input::all());

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),User::$rules);
            if($validator->passes()):
                $account = User::where('email',Input::get('email'))->first();
                if(is_null($account)):
                    if($account = self::getRegisterAccount(Input::all())):
                        if(Allow::enabled_module('downloads')):
                            if(!File::exists(base_path('usersfiles/account-').$account->id)):
                                File::makeDirectory(base_path('usersfiles/account-').$account->id,777,TRUE);
                            endif;
                        endif;
                        Mail::send('emails.auth.signup',array('account'=>$account),function($message){
                            $message->from('uspensky.pk@gmail.com','Monety.pro');
                            $message->to(Input::get('email'))->subject('Monety.pro - регистрация');
                        });
                        $json_request['responseText'] = 'Вы зарегистрированы. Мы отправили на email cсылку для активации аккаунта.';
                        $json_request['status'] = TRUE;
                    endif;
                else:

                endif;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function signupFL(){

        Helper::dd(Input::all());
    }
}