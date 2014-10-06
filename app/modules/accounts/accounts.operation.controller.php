<?php

class AccountsOperationController extends BaseController {

    public static $name = 'operation';
    public static $group = 'accounts';
    public static $entity = 'operation';
    public static $entity_name = 'Действия над пользователеми';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => Auth::user()->group()->pluck('name')), function() use ($class) {
                Route::get('repeated-send-mail/activation', array('as'=>'activation-repeated-sending-letter', 'uses' => $class.'@ActivationRepeatedSendingLetter'));
            });
            Route::group(array('before' => 'auth.status', 'prefix' => 'organization'), function() use ($class) {
                Route::get('registration/listener', array('as' => 'signup-listener', 'uses' => $class . '@signupListener'));

                Route::get('profile', array('as' => 'company-profile', 'uses' => $class . '@CompanyProfile'));
                Route::get('listeners/profile/{listener_id}', array('as' => 'company-listener-profile', 'uses' => $class . '@CompanyListenerProfile'));
                Route::get('listeners/profile/{listener_id}/edit', array('as' => 'company-listener-profile-edit', 'uses' => $class . '@CompanyListenerProfileEdit'));
                Route::patch('listeners/profile/{listener_id}/update', array('as' => 'company-listener-profile-update', 'uses' => $class . '@CompanyListenerProfileUpdate'));

                Route::get('orders', array('as' => 'company-orders', 'uses' => $class . '@CompanyOrdersList'));
                Route::get('listeners', array('as' => 'company-listeners', 'uses' => $class . '@CompanyListenersList'));
                Route::get('study', array('as' => 'company-study', 'uses' => $class . '@CompanyStudyProgressList'));
                Route::get('notifications', array('as' => 'company-notifications', 'uses' => $class . '@CompanyNotificationsList'));
            });
        endif;
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
    /********************************* COMPANY **********************************/
    /****************************************************************************/

    public function CompanyProfile(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE.keywords'),
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function CompanyListenerProfile($listener_id){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.keywords'),
        );
        $page_data['profile'] = User_listener::whereId($listener_id)
                ->where('organization_id',Auth::user()->id)
                ->where('active',1)
                ->with(array('study'=>function($query){
                    $query->with('order');
                    $query->with('course');
                }))
                ->firstOrFail();
//        Helper::dd($page_data['profile']);
        return View::make(Helper::acclayout('listeners-profile'),$page_data);
    }

    public function CompanyListenerProfileEdit($listener_id){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.keywords'),
        );
        $page_data['profile'] = User_listener::whereId($listener_id)
                ->where('organization_id',Auth::user()->id)
                ->where('active',1)
                ->firstOrFail();
        return View::make(Helper::acclayout('listeners-profile-edit'),$page_data);
    }

    public function CompanyListenerProfileUpdate($listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax() && isOrganization()):
            $validator = Validator::make(Input::all(),Listener::$update_rules);
            if($validator->passes()):
                if (self::ListenerAccountUpdate($listener_id,Input::all())):
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.success');
                    $json_request['redirect'] = URL::route('company-listener-profile',$listener_id);
                    $json_request['status'] = TRUE;
                else:
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function ListenerAccountUpdate($listener_id,$post){

        if($user = User::whereId($listener_id)->where('active',1)->first()):
            if($listener = Listener::where('user_id',$user->id)->where('organization_id',Auth::user()->id)->first()):
                $fio = explode(' ',$post['fio']);
                $user->name = (isset($fio[1]))?$fio[1]:'';
                $user->surname = (isset($fio[0]))?$fio[0]:'';
                $user->save();
                $user->touch();

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

                return TRUE;
            endif;
        else:
            return FALSE;
        endif;
    }

    public function CompanyOrdersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_ORDERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_ORDERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_ORDERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('orders-lists'),$page_data);
    }

    public function CompanyListenersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('listeners-lists'),$page_data);
    }

    public function ActivationRepeatedSendingLetter(){

        $user = Auth::user();
        $user->temporary_code = Str::random(24);
        $user->update();
        $user->touch();
        Mail::send('emails.repeated_sending.activation',array('account'=>$user),function($message) use ($user){
            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
            $message->to($user->email)->subject('ТехВуз.рф - Активация аккаунта');
        });
       return Redirect::to(AuthAccount::getGroupStartUrl())->with('message',Lang::get('interface.REPEATED_SENDING_LETTER.success'));
    }

    public function signupListener(){

        $page_data = array(
            'page_title'=> Lang::get('seo.REGISTER_LISTENER.title'),
            'page_description'=> Lang::get('seo.REGISTER_LISTENER.description'),
            'page_keywords'=> Lang::get('seo.REGISTER_LISTENER.keywords'),
        );
        return View::make(Helper::acclayout('listener-registration'),$page_data);
    }
    /**************************************************************************/

}