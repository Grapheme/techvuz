<?php

class AccountsOrganizationController extends BaseController {

    public static $name = 'company';
    public static $group = 'accounts';
    public static $entity = 'company';
    public static $entity_name = 'Действия организации';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (isOrganization()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'company'), function() use ($class) {
                Route::get('registration/listener', array('as' => 'signup-listener', 'uses' => $class . '@signupListener'));

                Route::get('profile', array('as' => 'organization-profile', 'uses' => $class . '@CompanyProfile'));
                Route::get('profile/edit', array('as' => 'organization-profile-edit', 'uses' => $class . '@CompanyProfileEdit'));
                Route::patch('profile/update', array('before' => 'csrf', 'as' => 'organization-profile-update', 'uses' => $class . '@CompanyProfileUpdate'));

                Route::get('listeners/profile/{listener_id}', array('as' => 'organization-listener-profile', 'uses' => $class . '@CompanyListenerProfile'));
                Route::get('listeners/profile/{listener_id}/edit', array('as' => 'organization-listener-profile-edit', 'uses' => $class . '@CompanyListenerProfileEdit'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf', 'as' => 'organization-listener-profile-update', 'uses' => $class . '@CompanyListenerProfileUpdate'));
                Route::delete('listeners/profile/{listener_id}/delete', array('as' => 'organization-listener-profile-delete', 'uses' => $class . '@CompanyListenerProfileDelete'));

                Route::get('orders', array('as' => 'organization-orders', 'uses' => $class . '@CompanyOrdersList'));
                Route::get('order/{order_id}', array('as' => 'organization-order', 'uses' => $class . '@CompanyOrderShow'));
                Route::delete('order/{order_id}/delete', array('as' => 'organization-order-delete', 'uses' => $class . '@CompanyDeleteOrder'));

                Route::get('listeners', array('as' => 'organization-listeners', 'uses' => $class . '@CompanyListenersList'));
                Route::get('study', array('as' => 'organization-study', 'uses' => $class . '@CompanyStudyProgressList'));
                Route::get('notifications', array('as' => 'organization-notifications', 'uses' => $class . '@CompanyNotificationsList'));
                Route::delete('notification/{notification_id}/delete', array('as' => 'organization-notification-delete', 'uses' => $class . '@CompanyNotificationDelete'));
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
        if(AccountsOrganizationController::activism()):
            return App::abort(404);
        endif;
        if(Request::ajax() && isOrganization()):
            $validator = Validator::make(Input::all(),Organization::$update_rules);
            if($validator->passes()):
                if (self::CompanyAccountUpdate(Input::all())):
                    Event::fire('moderator.update-profile-organization',array(array('accountID'=>0,'organization'=>User_organization::where('id',Auth::user()->id)->pluck('title'),'link'=>URL::to('moderator/companies/profile/'.Auth::user()->id))));
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.success');
                    $json_request['redirect'] = URL::route('organization-profile');
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
            $organization->fio_manager_rod = $post['fio_manager_rod'];
            $organization->manager = $post['manager'];
            $organization->manager_rod = $post['manager_rod'];
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
                ->where('active','>=',1)
                ->with(array('study'=>function($query){
                    $query->orderBy('over_status');
                    $query->with('order');
                    $query->with('course');
                    $query->with('final_test');
                }))
                ->first();
        if (!$page_data['profile']):
            App::abort(404);
        endif;
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
                ->where('active','>=',1)
            ->first();
        if (!$page_data['profile']):
            App::abort(404);
        endif;
        return View::make(Helper::acclayout('listeners-profile-edit'),$page_data);
    }

    public function CompanyListenerProfileUpdate($listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(self::activism($listener_id)):
            return App::abort(404);
        endif;
        if(Request::ajax() && isOrganization()):
            $validator = Validator::make(Input::all(),Listener::$update_rules);
            if($validator->passes()):
                if (self::ListenerAccountUpdate($listener_id,Input::all())):
                    Event::fire('moderator.update-profile-listener',array(array('accountID'=>0,'organization_link'=>URL::to('moderator/companies/profile/'.Auth::user()->id),'listener_link'=>URL::to('moderator/listeners/profile/'.$listener_id),'organization'=>User_organization::where('id',Auth::user()->id)->pluck('title'),'listener'=>Input::get('fio'))));
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.success');
                    $json_request['redirect'] = URL::route('organization-listener-profile',$listener_id);
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

    public function CompanyListenerProfileDelete($listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(self::activism($listener_id)):
            return App::abort(404);
        endif;
        if(Request::ajax() && isOrganization()):
            if(Listener::where('organization_id',Auth::user()->id)->where('user_id',$listener_id)->exists()):

                Event::fire('moderator.delete.company-listener', array(array('accountID' => 0,
                    'organization_link' => URL::to('moderator/companies/profile/' . Auth::user()->id),
                    'organization' => User_organization::where('id', Auth::user()->id)->pluck('title'),
                    'listener' => User_listener::where('id', $listener_id)->pluck('fio'))));

                Listener::where('user_id',$listener_id)->delete();
                User::where('id',$listener_id)->delete();
                $json_request['redirect'] = URL::route('organization-listeners');
                $json_request['status'] = TRUE;
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function ListenerAccountUpdate($listener_id,$post){

        if($user = User::where('id',$listener_id)->where('active','>=',1)->first()):
            if($listener = Listener::where('user_id',$user->id)->where('organization_id',Auth::user()->id)->first()):
                $fio = explode(' ',$post['fio']);
                $user->name = (isset($fio[1]))?$fio[1]:'';
                $user->surname = (isset($fio[0]))?$fio[0]:'';
                $user->save();
                $user->touch();

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
            ->with('listeners.course','listeners.course.seo','listeners.user_listener','payment','payment_numbers')
            ->first();
        if (!$order):
            return Redirect::route('organization-orders');
        endif;

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_ORDER.title'),
            'page_description'=> Lang::get('seo.COMPANY_ORDER.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_ORDER.keywords'),
            'order' => $order
        );
        return View::make(Helper::acclayout('order'),$page_data);
    }

    public function CompanyDeleteOrder($order_id){

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        if($order = Orders::where('payment_status',1)->findOrFail($order_id)):

            $zak_link = URL::to('moderator/companies/profile/'. $order->user_id);
            $zak_name = User_organization::where('id', $order->user_id)->pluck('title');

            Event::fire('moderator.delete.order', array(array('accountID' => 0,
                'order' => getOrderNumber($order),
                'organization_link' => $zak_link,
                'organization' => $zak_name)));

            Orders::where('payment_status',1)->findOrFail($order_id)->payment_numbers()->delete();
            if($orderListenersIDs = Orders::where('payment_status',1)->findOrFail($order_id)->listeners()->lists('id')):
                OrdersListenersTests::whereIn('order_listeners_id',$orderListenersIDs)->delete();
                Orders::where('payment_status',1)->findOrFail($order_id)->listeners()->delete();
            endif;
            $order->delete();
            $json_request['status'] = TRUE;
            $json_request['responseText'] = 'Выполенено';
        endif;
        return Response::json($json_request, 200);
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

    public function CompanyNotificationDelete($notification_id){

        if ($notification_id == 'all'):
            $messages = Dictionary::valuesBySlug('system-messages',function($query){
                $query->filter_by_field('user_id','=',Auth::user()->id);
            });
            foreach($messages as $message):
                if($IDs = array_keys(modifyKeys($message->fields,'id'))):
                    DicFieldVal::whereIn('id',$IDs)->delete();
                endif;
            endforeach;
            if($IDs = array_keys(modifyKeys($messages,'id'))):
                DicFieldVal::whereIn('id',$IDs)->delete();
            endif;
        elseif($notification_id == 'selected' && Input::has('messages')):
            $notificationIDs = Input::get('messages');
            $messages = Dictionary::valuesBySlug('system-messages',function($query) use ($notificationIDs) {
                $query->whereIn('dictionary_values.id',$notificationIDs);
                $query->filter_by_field('user_id','=',Auth::user()->id);
            });
            foreach($messages as $message):
                if($IDs = array_keys(modifyKeys($message->fields,'id'))):
                    DicFieldVal::whereIn('id',$IDs)->delete();
                endif;
            endforeach;
            if($IDs = array_keys(modifyKeys($messages,'id'))):
                DicFieldVal::whereIn('id',$IDs)->delete();
            endif;
        else:
            DicVal::where('id',$notification_id)->delete();
            DicFieldVal::where('dicval_id',$notification_id)->delete();
        endif;
        return Redirect::back();
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

    public static function activism($listenerIDs = NULL){

        $result = FALSE;
        if (is_null($listenerIDs)):
            $listenerIDs = User_listener::where('organization_id',Auth::user()->id)->lists('id');
        elseif(is_array($listenerIDs) == FALSE):
            $listenerIDs = array($listenerIDs);
        endif;
        if (!empty($listenerIDs)):
            foreach(OrderListeners::whereIn('user_id',$listenerIDs)->with('order')->get() as $order):
                if ($order->order->close_status == 0):
                    $result = TRUE;
                    break;
                endif;
            endforeach;
        endif;
        return $result;
    }
}