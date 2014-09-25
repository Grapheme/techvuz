<?php

class AccountsRegisterController extends BaseController {

    public static $name = 'registration';
    public static $group = 'accounts';
    public static $entity = 'registration';
    public static $entity_name = 'Регистрация пользователей';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'guest.register', 'prefix' => ''), function() use ($class) {
            Route::post('registration/ul', array('as' => 'signup-ul', 'uses' => $class.'@signupUL'));
            Route::post('registration/fl', array('as' => 'signup-fl', 'uses' => $class.'@signupFL'));
        });
        Route::group(array('before' => 'guest', 'prefix' => ''), function() use ($class) {
            Route::get('registration/activation/{user_id}/{activate_code}', array('as' => 'signup-activation', 'uses' => $class.'@activation'));
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

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),Organization::$rules);
            if($validator->passes()):
                if(User::where('email',Input::get('email'))->exists() == FALSE):
                    Config::set('temp.account_password', Str::random(12));
                    if($account = self::getRegisterULAccount(Input::all())):
                        Mail::send('emails.auth.signup',array('account'=>$account),function($message){
                            $message->from('support@grapheme.ru','ТехВуз.рф');
                            $message->to(Input::get('email'))->subject('ТехВуз.рф - регистрация');
                        });
                        Auth::login(User::find($account->id));
                        if (Auth::check()):
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success_login');
                        else:
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        endif;
                        $json_request['status'] = TRUE;
                    endif;
                else:
                    $json_request['responseText'] = Lang::get('interface.SIGNUP.email_exist');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.SIGNUP.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function signupFL(){

        Helper::tad(Input::all());

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),Organization::$rules);
            if($validator->passes()):
                if(User::where('email',Input::get('email'))->exists() == FALSE):
                    if($account = self::getRegisterULAccount(Input::all())):
                        Mail::send('emails.auth.signup',array('account'=>$account),function($message){
                            $message->from('uspensky.pk@gmail.com','Monety.pro');
                            $message->to(Input::get('email'))->subject('Monety.pro - регистрация');
                        });
                        $json_request['responseText'] = Lang::get('SIGNUP.success');
                        $json_request['status'] = TRUE;
                    endif;
                else:
                    $json_request['responseText'] = Lang::get('SIGNUP.email_exist');
                endif;
            else:
                $json_request['responseText'] = Lang::get('SIGNUP.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    /**************************************************************************/

    private function getRegisterULAccount($post = NULL){

        $user = new User;
        $organization = new Organization;

        if(!is_null($post)):
            $fio = explode(' ',$post['name']);
            $user->group_id = $post['group_id'];
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->email = $post['email'];
            $user->active = 2;
            $user->password = Hash::make(Config::get('temp.account_password'));
            $user->photo = '';
            $user->thumbnail = '';
            $user->temporary_code = Str::random(24);
            $user->code_life = myDateTime::getFutureDays(5);
            $user->save();
            $user->touch();

            $organization->user_id = $user->id;
            $organization->title = $post['title'];
            $organization->fio_manager = $post['fio_manager'];
            $organization->manager = $post['manager'];
            $organization->statutory = $post['statutory'];
            $organization->inn = $post['inn'];
            $organization->kpp = $post['kpp'];
            $organization->postaddress = $post['postaddress'];
            $organization->account_type = $post['account_type'];
            $organization->account_number = $post['account_number'];
            $organization->bank = $post['bank'];
            $organization->bik = $post['bik'];
            $organization->name = $post['name'];
            $organization->phone = $post['phone'];
            $organization->save();
            $organization->touch();

            return User_organization::where('id',$user->id)->first();
        endif;
        return FALSE;
    }
}