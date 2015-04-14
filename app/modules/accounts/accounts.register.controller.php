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

        Route::group(array('before' => 'auth.status', 'prefix' => 'company'), function() use ($class) {
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
                        #Event::fire('organization.approve-email',array(array('accountID'=>$account->id)));
                        Event::fire('moderator.register-organization',array(array('accountID'=>0,'organization'=>User_organization::where('id',$account->id)->pluck('title'),'link'=>URL::to('moderator/companies/profile/'.$account->id))));
                        if (Auth::check()):
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success_login');
                        else:
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        endif;
                        $json_request['redirect'] = AuthAccount::getGroupStartUrl();
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
                        Event::fire('moderator.register-individual',array(array('accountID'=>0,'listener'=>User_individual::where('id',$account->id)->pluck('fio'),'link'=>URL::to('moderator/listeners/profile/'.$account->id))));
                        if (Auth::check()):
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success_login');
                        else:
                            $json_request['responseText'] = Lang::get('interface.SIGNUP.success');
                        endif;
                        $json_request['redirect'] = AuthAccount::getGroupStartUrl();
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
                        $json_request['responseText'] .= '<div class="desc margin-bottom-30">На '.Input::get('email').' '.Lang::get('interface.SIGNUP_LISTENER.success_desc').'</div>';
                        $json_request['responseText'] .= '<a class="btn btn--bordered btn--blue margin-right-20 margin-bottom-10" href="'.URL::route('signup-listener').'">'.Lang::get('interface.SIGNUP_LISTENER.next_operation_1').'</a>';
                        if (hasCookieData('ordering')):
                            $json_request['responseText'] .= '<a class="btn btn--bordered btn--blue margin-right-20 margin-bottom-10" href="'.URL::route('ordering-select-listeners').'">'.Lang::get('interface.SIGNUP_LISTENER.next_operation_2').'</a>';
                        endif;
                        Event::fire('moderator.register-listener',array(array('accountID'=>0,'organization_link'=>URL::to('moderator/companies/profile/'.Auth::user()->id),'listener_link'=>URL::to('moderator/listeners/profile/'.$account->id),'organization'=>User_organization::where('id',Auth::user()->id)->pluck('title'),'listener'=>Input::get('fio'))));
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
            if(isOrganization()):
                Event::fire('organization.approved-email',array(array('accountID'=>Auth::user()->id)));
                #Event::fire('organization.select-courses',array(array('accountID'=>Auth::user()->id)));
                #Event::fire('organization.register-listeners',array(array('accountID'=>Auth::user()->id)));
                #Event::fire('account.approved-email',array(array('accountID'=>Auth::user()->id)));
            elseif(isCompanyListener()):
                Event::fire('listener.approved-email',array(array('accountID'=>Auth::user()->id)));
            elseif(isIndividual()):
                Event::fire('individual.approved-email',array(array('accountID'=>Auth::user()->id)));
            endif;
            return Redirect::to(AuthAccount::getGroupStartUrl());
        else:
            return Redirect::to('/')->with('message.status','error')->with('message.text','Код активации не действителен.');
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
            $organization->fio_manager_rod = $post['fio_manager_rod'];
            $organization->manager = $post['manager'];
            $organization->manager_rod = $post['manager_rod'];
            $organization->statutory = $post['statutory'];
            $organization->ogrn = $post['ogrn'];
            $organization->inn = $post['inn'];
            $organization->kpp = $post['kpp'];
            $organization->uraddress = $post['uraddress'];
            if (empty($post['postaddress'])):
                $organization->postaddress = $organization->uraddress;
            else:
                $organization->postaddress = $post['postaddress'];
            endif;
            $organization->account_type = $post['account_type'];
            $organization->account_number = $post['account_number'];
            $organization->account_kor_number = $post['account_kor_number'];
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
            $individual->fio_rod = $post['fio_rod'];
            $individual->passport_seria = $post['passport_seria'];
            $individual->passport_number = $post['passport_number'];
            $individual->passport_data = $post['passport_data'];
            $individual->passport_date = $post['passport_date'];
            $individual->code = $post['code'];

            $individual->postaddress = $post['postaddress'];
            $individual->phone = $post['phone'];

            $individual->position = $post['position'];
            $individual->education = $post['education'];
            $individual->document_education = $post['document_education'];
            $individual->specialty = $post['specialty'];
            $individual->educational_institution = $post['educational_institution'];

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
            $listener->fio_dat = $post['fio_dat'];
            $listener->position = $post['position'];
            $listener->postaddress = $post['postaddress'];
            $listener->phone = $post['phone'];
            $listener->education = $post['education'];
            $listener->education_document_data = $post['education_document_data'];
            $listener->educational_institution = $post['educational_institution'];
            $listener->specialty = $post['specialty'];
            $listener->save();
            $listener->touch();

            return User_listener::where('id',$user->id)->first();
        endif;
        return FALSE;
    }

}