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
            Route::post('registration/ul', array('before' => 'csrf', 'as' => 'signup-ul', 'uses' => $class . '@signupUL'));
            Route::post('registration/fl', array('before' => 'csrf', 'as' => 'signup-fl', 'uses' => $class . '@signupFL'));
        });
        Route::group(array('before' => 'guest.auth', 'prefix' => ''), function() use ($class) {
            Route::get('registration/activation/{activate_code}', array('as' => 'signup-activation', 'uses' => $class . '@activation'));
        });

        Route::group(array('before' => 'auth.status', 'prefix' => 'organization'), function() use ($class) {
            Route::post('registration/listener', array('before' => 'csrf', 'as' => 'signup-listener', 'uses' => $class . '@signupListener'));
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
                            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                            $message->to(Input::get('email'))->subject('ТехВуз.рф - регистрация');
                        });
                        Auth::login(User::find($account->id));
                        if (Auth::check()):
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success_login');
                        else:
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        endif;
                        $json_request['redirect'] = AuthAccount::getStartPage();
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

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),Individual::$rules);
            if($validator->passes()):
                if(User::where('email',Input::get('email'))->exists() == FALSE):
                    Config::set('temp.account_password', Str::random(12));
                    if($account = self::getRegisterFLAccount(Input::all())):
                        Mail::send('emails.auth.signup',array('account'=>$account),function($message){
                            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                            $message->to(Input::get('email'))->subject('ТехВуз.рф - регистрация');
                        });
                        Auth::login(User::find($account->id));
                        if (Auth::check()):
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success_login');
                        else:
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        endif;
                        $json_request['redirect'] = AuthAccount::getStartPage();
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

    public function signupListener(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax() && isOrganization()):
            $validator = Validator::make(Input::all(),Listener::$rules);
            if($validator->passes()):
                if(User::where('email',Input::get('email'))->exists() == FALSE):
                    Config::set('temp.account_password', Str::random(12));
                    if($account = self::getRegisterListenerAccount(Input::all())):
                        Mail::send('emails.auth.signup-listener',array('account'=>$account),function($message){
                            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                            $message->to(Input::get('email'))->subject('ТехВуз.рф - регистрация');
                        });
                        $json_request['responseText'] = '<h3 class="margin-bottom-10">'.Lang::get('interface.SIGNUP_LISTENER.success').'</h3>';
                        $json_request['responseText'] .= '<div class="desc margin-bottom-30">'.Lang::get('interface.SIGNUP_LISTENER.success_desc').'</div>';
                        $json_request['responseText'] .= '<a class="btn btn--bordered btn--blue margin-right-20 margin-bottom-10" href="'.URL::route('signup-listener').'">'.Lang::get('interface.SIGNUP_LISTENER.next_operation_1').'</a>';
                        if (hasCookieData('ordering')):
                            $json_request['responseText'] .= '<a class="btn btn--bordered btn--blue margin-right-20 margin-bottom-10" href="'.URL::route('ordering-select-listeners').'">'.Lang::get('interface.SIGNUP_LISTENER.next_operation_2').'</a>';
                        endif;
                        $json_request['status'] = TRUE;
                    endif;
                else:
                    $json_request['responseText'] = Lang::get('interface.SIGNUP_LISTENER.email_exist');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.SIGNUP_LISTENER.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function activation($temporary_key = ''){

        if ($account = User::whereIn('active',array(1,2))->where('temporary_code',$temporary_key)->where('code_life','>=',time())->first()):
            $account->code_life = 0;
            $account->temporary_code = '';
            $account->active = 1;
            $account->save();
            $account->touch();
            Auth::login($account);
            return Redirect::to(AuthAccount::getStartPage())->with('message.text',Lang::get('interface.ACTIVATE.success'))->with('message.status','activation');
        else:
            return App::abort(404);
        endif;
    }

    /**************************************************************************/

    private function getRegisterULAccount($post = NULL){

        $user = new User;
        $organization = new Organization;

        if(!is_null($post)):
            $fio = explode(' ',$post['name']);
            $user->group_id = Group::where('name','organization')->pluck('id');
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

    private function getRegisterFLAccount($post = NULL){

        $user = new User;
        $individual = new Individual;

        if(!is_null($post)):
            $fio = explode(' ',$post['fio']);
            $user->group_id = Group::where('name','individual')->pluck('id');
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

            $individual->user_id = $user->id;
            $individual->fio = $post['fio'];
            $individual->position = $post['position'];
            $individual->inn = $post['inn'];
            $individual->postaddress = $post['postaddress'];
            $individual->phone = $post['phone'];
            $individual->save();
            $individual->touch();

            return User_individual::where('id',$user->id)->first();
        endif;
        return FALSE;
    }

    private function getRegisterListenerAccount($post = NULL){

        $user = new User;
        $listener = new Listener;

        if(!is_null($post)):
            $fio = explode(' ',$post['fio']);
            $user->group_id = Group::where('name','listener')->pluck('id');
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

            $listener->user_id = $user->id;
            $listener->organization_id = Auth::user()->id;
            $listener->fio = $post['fio'];
            $listener->position = $post['position'];
            $listener->postaddress = $post['postaddress'];
            $listener->phone = $post['phone'];
            $listener->education = $post['education'];
            $listener->place_work = $post['place_work'];
            $listener->year_study = $post['year_study'];
            $listener->specialty = $post['specialty'];
            $listener->save();
            $listener->touch();

            return User_listener::where('id',$user->id)->first();
        endif;
        return FALSE;
    }

}