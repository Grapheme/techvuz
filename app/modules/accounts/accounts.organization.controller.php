<?php

class AccountsOrganizationController extends BaseController {

    public static $name = 'organization';
    public static $group = 'accounts';
    public static $entity = 'organization';
    public static $entity_name = 'Действия организации';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (isOrganization()):
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function() use ($class) {
                Route::get('registration/listener', array('as' => 'signup-listener', 'uses' => $class . '@signupListener'));

                Route::get('profile', array('as' => 'company-profile', 'uses' => $class . '@CompanyProfile'));
                Route::get('profile/edit', array('as' => 'company-profile-edit', 'uses' => $class . '@CompanyProfileEdit'));
                Route::patch('profile/update', array('before' => 'csrf', 'as' => 'company-profile-update', 'uses' => $class . '@CompanyProfileUpdate'));

                Route::get('listeners/profile/{listener_id}', array('as' => 'company-listener-profile', 'uses' => $class . '@CompanyListenerProfile'));
                Route::get('listeners/profile/{listener_id}/edit', array('as' => 'company-listener-profile-edit', 'uses' => $class . '@CompanyListenerProfileEdit'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf', 'as' => 'company-listener-profile-update', 'uses' => $class . '@CompanyListenerProfileUpdate'));

                Route::get('orders', array('as' => 'company-orders', 'uses' => $class . '@CompanyOrdersList'));
                Route::get('order/{order_id}', array('as' => 'company-order', 'uses' => $class . '@CompanyOrderShow'));

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

    public function CompanyProfile(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE.keywords'),
            'profile' => User_organization::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function CompanyProfileEdit(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE.keywords'),
            'profile' => User_organization::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile-edit'),$page_data);
    }

    public function CompanyProfileUpdate(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax() && isOrganization()):
            $validator = Validator::make(Input::all(),Organization::$update_rules);
            if($validator->passes()):
                if (self::CompanyAccountUpdate(Input::all())):
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.success');
                    $json_request['redirect'] = URL::route('company-profile');
                    $json_request['status'] = TRUE;
                else:
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.fail');
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function CompanyAccountUpdate($post){

        $user = Auth::user();
        if($organization = Organization::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['name']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->save();
            $user->touch();

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

            return TRUE;
        endif;
    }

    public function CompanyListenerProfile($listener_id){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.keywords'),
        );
        $page_data['profile'] = User_listener::where('id',$listener_id)
                ->where('organization_id',Auth::user()->id)
                ->where('active',1)
                ->with(array('study'=>function($query){
                    $query->with('order');
                    $query->with('course');
                }))
                ->firstOrFail();
        return View::make(Helper::acclayout('listeners-profile'),$page_data);
    }

    public function CompanyListenerProfileEdit($listener_id){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE_LISTENER.keywords'),
        );
        $page_data['profile'] = User_listener::where('id',$listener_id)
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

        if($user = User::where('id',$listener_id)->where('active',1)->first()):
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

    public function CompanyOrderShow($order_id){

        $order = Orders::where('id',$order_id)
            ->where('user_id',Auth::user()->id)
            ->where('completed',1)
            ->where('archived',0)
            ->with('listeners.course','listeners.user_listener','payment')
            ->first();
        if (!$order):
            return Redirect::route('company-orders');
        endif;

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_ORDER.title'),
            'page_description'=> Lang::get('seo.COMPANY_ORDER.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_ORDER.keywords'),
            'order' => $order
        );
        return View::make(Helper::acclayout('order'),$page_data);
    }

    public function CompanyListenersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('listeners-lists'),$page_data);
    }

    public function CompanyStudyProgressList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('listeners-study-progress'),$page_data);
    }

    public function CompanyNotificationsList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.keywords'),
        );
        return View::make(Helper::acclayout('notifications'),$page_data);
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