<?php

class AccountsIndividualController extends BaseController {

    public static $name = 'individual';
    public static $group = 'accounts';
    public static $entity = 'individual';
    public static $entity_name = 'Действия индивидуального слушателя';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (isIndividual()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'individual-listener'), function() use ($class) {
                Route::get('profile', array('as' => 'individual-profile', 'uses' => $class . '@IndividualProfile'));
                Route::get('profile/edit', array('as' => 'individual-profile-edit', 'uses' => $class . '@IndividualProfileEdit'));
                Route::patch('profile/update', array('before' => 'csrf', 'as' => 'individual-profile-update', 'uses' => $class . '@IndividualProfileUpdate'));

                Route::get('orders', array('as' => 'individual-orders', 'uses' => $class . '@IndividualOrdersList'));
                Route::get('order/{order_id}', array('as' => 'individual-order', 'uses' => $class . '@IndividualOrderShow'));
                Route::delete('order/{order_id}/delete', array('as' => 'individual-order-delete', 'uses' => $class . '@IndividualDeleteOrder'));

                Route::get('study', array('as' => 'individual-study', 'uses' => $class . '@ListenerStudyList'));
                Route::get('study/course/{course_translite_title}', array('as' => 'individual-study-course', 'uses' => $class . '@ListenerStudyCourse'));
                Route::post('study/course/{study_course_id}/lecture/{lecture_id}/download', array('before' => 'csrf', 'as' => 'listener-study-download-lecture', 'uses' => $class . '@ListenerStudyLectureDownload'));
                Route::post('study/course/{study_course_id}/lectures/download', array('before' => 'csrf', 'as' => 'listener-study-download-lectures', 'uses' => $class . '@ListenerStudyLecturesDownload'));
                Route::get('study/course/{course_translite_title}/test/{test_id}', array('as' => 'listener-study-testing', 'uses' => $class . '@ListenerStudyTesting'));
                Route::post('study/course/{course_id}/test/{test_id}/finish', array('before' => 'csrf', 'as' => 'listener-study-test-finish', 'uses' => $class . '@ListenerStudyTestFinish'));
                Route::get('study/course/{course_translite_title}/test/{study_test_id}/result', array('as' => 'listener-study-test-result', 'uses' => $class . '@ListenerStudyTestResult'));


                Route::get('notifications', array('as' => 'individual-notifications', 'uses' => $class . '@IndividualNotificationsList'));
                Route::delete('notification/{notification_id}/delete', array('as' => 'individual-notification-delete', 'uses' => $class . '@IndividualNotificationDelete'));
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

    public function IndividualProfile(){
        $page_data = array(
            'page_title'=> Lang::get('seo.INDIVIDUAL_PROFILE.title'),
            'page_description'=> Lang::get('seo.INDIVIDUAL_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.INDIVIDUAL_PROFILE.keywords'),
            'profile' => User_individual::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function IndividualProfileEdit(){

        $page_data = array(
            'page_title'=> Lang::get('seo.INDIVIDUAL_PROFILE.title'),
            'page_description'=> Lang::get('seo.INDIVIDUAL_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.INDIVIDUAL_PROFILE.keywords'),
            'profile' => User_individual::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile-edit'),$page_data);
    }

    public function IndividualProfileUpdate(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(AccountsIndividualController::activism()):
            return App::abort(404);
        endif;
        if(Request::ajax() && isIndividual()):
            $validator = Validator::make(Input::all(),Individual::$update_rules);
            if($validator->passes()):
                if (self::IndividualAccountUpdate(Input::all())):
                    Event::fire('moderator.update-profile-individual',array(array('accountID'=>0,'listener'=>User_individual::where('id',Auth::user()->id)->pluck('fio'))));
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_INDIVIDUAl.success');
                    $json_request['redirect'] = URL::route('individual-profile');
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

    private function IndividualAccountUpdate($post){

        $user = Auth::user();
        if($individual = Individual::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['name']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->save();
            $user->touch();

            $individual->fio = $post['fio'];
            $individual->fio_rod = $post['fio_rod'];
            $individual->passport_seria = $post['passport_seria'];
            $individual->passport_number = $post['passport_number'];
            $individual->passport_data = $post['passport_data'];
            $individual->passport_date = $post['passport_date'];
            $individual->postaddress = $post['postaddress'];
            $individual->code = $post['code'];
            $individual->phone = $post['phone'];
            $individual->position = $post['position'];
            $individual->education = $post['education'];
            $individual->document_education = $post['document_education'];
            $individual->specialty = $post['specialty'];
            $individual->educational_institution = $post['specialty'];

            $individual->save();
            $individual->touch();

            return TRUE;
        endif;
    }

    public function IndividualOrdersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_ORDERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_ORDERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_ORDERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('orders-lists'),$page_data);
    }

    public function IndividualOrderShow($order_id){

        $order = Orders::where('id',$order_id)
            ->where('user_id',Auth::user()->id)
            ->where('completed',1)
            ->where('archived',0)
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

    public function IndividualDeleteOrder($order_id){

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        if($order = Orders::where('payment_status',1)->findOrFail($order_id)):
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

    public function IndividualNotificationsList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.keywords'),
        );
        return View::make(Helper::acclayout('notifications'),$page_data);
    }

    public function IndividualNotificationDelete($notification_id){

        if ($notification_id == 'all'):
            $messages = Dictionary::valuesBySlug('system-messages',function($query){
                $query->filter_by_field('user_id',Auth::user()->id);
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

    /**************************************************************************/

    public function ListenerStudyList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('study-progress'),$page_data);
    }

    /**************************************************************************/

    public static function activism(){

        $result = FALSE;
        foreach(OrderListeners::where('user_id',Auth::user()->id)->with('order')->get() as $order):
            if ($order->order->close_status == 0):
                $result = TRUE;
                break;
            endif;
        endforeach;
        return $result;
    }
}