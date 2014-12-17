<?php

class AccountsModeratorController extends BaseController {

    public static $name = 'moderator';
    public static $group = 'accounts';
    public static $entity = 'moderator';
    public static $entity_name = 'Кабинет модератор';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;

        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function() use ($class) {
                Route::get('companies', array('as' => 'moderator-companies-list', 'uses' => $class . '@CompaniesList'));
                Route::get('companies/profile/{company_id}', array('as' => 'moderator-company-profile', 'uses' => $class . '@CompanyProfile'));
                Route::get('companies/profile/{company_id}/edit', array('as' => 'moderator-company-profile-edit', 'uses' => $class . '@CompanyProfileEdit'));
                Route::patch('companies/profile/{company_id}/update', array('as' => 'moderator-company-profile-update', 'uses' => $class . '@CompanyProfileUpdate'));

                Route::get('orders', array('as' => 'moderator-orders-list', 'uses' => $class . '@OrdersList'));
                Route::get('order/{order_id}/extended', array('as' => 'moderator-order-extended', 'uses' => $class . '@OrderExtendedView'));
                Route::post('order/{order_id}/extended/set-access', array('as' => 'order-listener-access', 'uses' => $class . '@changeOrderListenerAccess'));
                Route::post('order/{order_id}/extended/set-status', array('as' => 'change-order-status', 'uses' => $class . '@changeOrderStatus'));
                Route::get('order/{order_id}/edit', array('as' => 'moderator-order-edit', 'uses' => $class . '@OrderEdit'));
                Route::patch('order/{order_id}/update', array('as' => 'moderator-order-update', 'uses' => $class . '@OrderUpdate'));

                Route::delete('order/{order_id}/arhived', array('as' => 'moderator-order-arhived', 'uses' => $class . '@arhivedOrder'));
                Route::delete('order/{order_id}/delete', array('as' => 'moderator-order-delete', 'uses' => $class . '@deleteOrder'));

                Route::post('order/{order_id}/payment-number/store', array('before' => 'csrf', 'as' => 'payment-order-number-store', 'uses' => $class . '@OrderPaymentNumberStore'));
                Route::patch('order/{order_id}/payment-number/update', array('before' => 'csrf', 'as' => 'payment-order-number-update', 'uses' => $class . '@OrderPaymentNumberUpdate'));
                Route::delete('order/{order_id}/payment-number/delete/{payment_order_id}', array('before' => 'csrf', 'as' => 'payment-order-number-delete', 'uses' => $class . '@OrderPaymentNumberDelete'));

                Route::get('listeners', array('as' => 'moderator-listeners-list', 'uses' => $class . '@ListenersList'));
                Route::get('listeners/profile/{listener_id}', array('as' => 'moderator-listener-profile', 'uses' => $class . '@ListenerProfile'));
                Route::get('listeners/profile/{listener_id}/edit', array('as' => 'moderator-listener-profile-edit', 'uses' => $class . '@ListenerProfileEdit'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf', 'as' => 'moderator-listener-profile-update', 'uses' => $class . '@ListenerProfileUpdate'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf', 'as' => 'moderator-listener-profile-update', 'uses' => $class . '@ListenerProfileUpdate'));

                Route::get('statistic', array('as' => 'moderator-statistic', 'uses' => $class . '@statistic'));
                Route::post('statistic-set-period', array('as' => 'moderator-statistic-set-period', 'uses' => $class . '@statisticSetPeriod'));

                Route::get('notifications', array('as' => 'moderator-notifications', 'uses' => $class . '@NotificationsList'));
                Route::delete('notification/{notification_id}/delete', array('as' => 'moderator-notification-delete', 'uses' => $class . '@NotificationDelete'));
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

    public function NotificationsList(){

        $page_data = array(
            'page_title'=> 'Системные сообщения',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('notifications'),$page_data);
    }

    public function NotificationDelete($notification_id){

        if ($notification_id == 'all'):
            $messages = Dictionary::valuesBySlug('system-messages',function($query){
                $query->filter_by_field('user_id',0);
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
    /****************************************************************************/
    /******************************* КОМПАНИИ ***********************************/
    /****************************************************************************/
    public function CompaniesList(){

        $page_data = array(
            'page_title'=> 'Список компаний',
            'page_description'=> '',
            'page_keywords'=> '',
            'companies'=> array()
        );
        if ($companies_list = User_organization::orderBy('created_at','DESC')->with('orders.listeners','orders.payment_numbers')->get()):
            $companies = array();
            foreach($companies_list as $index => $company):
                $companies[$index]['id'] = $company->id;
                $companies[$index]['title'] = $company->title;
                $companies[$index]['created_at'] = $company->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y");
                $companies[$index]['manager'] = $company->manager;
                $companies[$index]['fio_manager'] = $company->fio_manager;
                $companies[$index]['email'] = $company->email;
                $companies[$index]['orders_count'] = $company->orders->count();
                $companies[$index]['discount'] = $company->discount;
                $companies[$index]['orders_earnings'] = array('total_earnings'=>0,'real_earnings'=>0);
                if ($company->orders->count()):
                    foreach ($company->orders as $order):
                        if ($order->listeners->count()):
                            foreach ($order->listeners as $listener):
                                $companies[$index]['orders_earnings']['total_earnings'] += $listener->price;
                            endforeach;
                        endif;
                        if ($order->payment_numbers->count()):
                            foreach ($order->payment_numbers as $payment_number):
                                $companies[$index]['orders_earnings']['real_earnings'] += $payment_number->price;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
            $page_data['companies'] = $companies;
        endif;
        return View::make(Helper::acclayout('companies'),$page_data);
    }

    public function CompanyProfile($company_id){

        $page_data = array(
            'page_title'=> 'Просмотр профиля компании',
            'page_description'=> '',
            'page_keywords'=> '',
            'profile' => array(),
            'listeners' => array(),
            'orders' => array(),

        );
        if($page_data['profile'] = User_organization::where('id',$company_id)->first()):
            $page_data['listeners'] = User_listener::where('organization_id',$company_id)->orderBy('created_at','DESC')->get();
            $page_data['orders'] = Orders::where('user_id',$company_id)->with('payment')->with('listeners')->get();
            return View::make(Helper::acclayout('company-profile'),$page_data);
        else:
            App::abort(404);
        endif;
    }

    public function CompanyProfileEdit($company_id){

        $page_data = array(
            'page_title'=> 'Редактирование профиля компании',
            'page_description'=> '',
            'page_keywords'=> '',
            'profile' => array(),
        );
        if($page_data['profile'] = User_organization::where('id',$company_id)->first()):
            return View::make(Helper::acclayout('company-profile-edit'),$page_data);
        else:
            App::abort(404);
        endif;
    }

    public function CompanyProfileUpdate($company_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),Organization::$moderator_rules);
            if($validator->passes()):
                if (self::CompanyAccountUpdate($company_id,Input::all())):
                    Event::fire(Route::currentRouteName(), array(array('title'=>'Организация: '.User_organization::where('id',$company_id)->pluck('title'))));
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.success');
                    $json_request['redirect'] = URL::route('moderator-company-profile',$company_id);
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

    private function CompanyAccountUpdate($company_id,$post){

        $user = User::findOrFail($company_id);
        if($organization = Organization::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['name']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->email = $post['email'];
            if($post['active'] == 1 && ($user->active == 0 || $user->active == 2) ):
                Event::fire('moderator-company-profile-activated', array(array('title'=>$post['title'])));
            endif;
            $user->active = $post['active'];
            if($post['active'] == 1):
                $user->temporary_code = '';
                $user->code_life = 0;
            endif;
            $user->save();
            $user->touch();

            $organization->title = $post['title'];
            $organization->fio_manager = $post['fio_manager'];
            $organization->fio_manager_rod = $post['fio_manager_rod'];
            $organization->manager = $post['manager'];
            $organization->statutory = $post['statutory'];
            $organization->ogrn = $post['ogrn'];
            $organization->inn = $post['inn'];
            $organization->kpp = $post['kpp'];
            $organization->uraddress = $post['uraddress'];
            $organization->postaddress = $post['postaddress'];
            $organization->account_type = $post['account_type'];
            $organization->account_number = $post['account_number'];
            $organization->account_kor_number = $post['account_kor_number'];
            $organization->bank = $post['bank'];
            $organization->bik = $post['bik'];
            $organization->name = $post['name'];
            $organization->phone = $post['phone'];
            $organization->discount = $post['discount'];
            if($organization->moderator_approve == 0 && $post['moderator_approve'] == 1):
                Event::fire('moderator-company-profile-approved', array(array('title'=>$post['title'])));
                Event::fire('account.approved-profile',array(array('accountID'=>$user->id)));
            endif;
            $organization->moderator_approve = $post['moderator_approve'];
            $organization->save();
            $organization->touch();

            return TRUE;
        endif;
    }
    /****************************************************************************/
    /******************************** ЗАКАЗЫ ************************************/
    /****************************************************************************/
    public function OrdersList(){

        $page_data = array(
            'page_title'=> 'Список заказов',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('orders'),$page_data);
    }

    public function OrderExtendedView($order_id){

        $page_data = array(
            'page_title'=> 'Просмотр заказа',
            'page_description'=> '',
            'page_keywords'=> '',
            'order' => array(),
            'courses' => array()
        );
        if($page_data['order'] = Orders::where('id',$order_id)->with('payment_numbers','organization','individual')->first()):
            $courses = array();
            foreach(Orders::where('id',$order_id)->first()->listeners()->with('user_listener','course')->get() as $course):
                $courses[$course->course_id]['course']['code'] = $course->course->code;
                $courses[$course->course_id]['course']['title'] = $course->course->title;
                $courses[$course->course_id]['course']['description'] = $course->course->description;
                $courses[$course->course_id]['course']['price'] = $course->course->price;
                $courses[$course->course_id]['course']['hours'] = $course->course->hours;
                $courses[$course->course_id]['listeners'][] = $course;
            endforeach;
            $page_data['courses'] = $courses;
            return View::make(Helper::acclayout('order'),$page_data);
        else:
            App::abort(404);
        endif;

    }

    public function OrderEdit($order_id){

        $page_data = array(
            'page_title'=> 'Редактирование заказа',
            'page_description'=> '',
            'page_keywords'=> '',
            'order' => Orders::where('id',$order_id)->with('contract','invoice','act')->first()
        );
        return View::make(Helper::acclayout('order-edit'),$page_data);
    }

    public function OrderUpdate($order_id){

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        if($order = Orders::where('id',$order_id)->first()):
            $date = new myDateTime();
            $order->number = (int)Input::get('number');
            $order->created_at = $date->setDateString(Input::get('created_at'))->format('Y-m-d H:i:s');;
            #$order->payment_date = $date->setDateString(Input::get('payment_date'))->format('Y-m-d H:i:s');
            $order->study_date = $date->setDateString(Input::get('study_date'))->format('Y-m-d H:i:s');
            if (!empty($order->study_date)):
                $order->study_status = 1;
            else:
                $order->study_status = 0;
            endif;
            $order->contract_id = ExtForm::process('upload', @Input::all()['contract']);
            $order->invoice_id = ExtForm::process('upload', @Input::all()['invoice']);
            $order->act_id = ExtForm::process('upload', @Input::all()['act']);
            $order->save();
            $order->touch();
            Event::fire(Route::currentRouteName(), array(array('title'=>'№'.getOrderNumber($order))));
            $json_request['responseText'] = 'Выполенено';
            $json_request['status'] = TRUE;
            $json_request['redirect'] = URL::route('moderator-order-extended',$order_id);
        endif;
        return Response::json($json_request, 200);
    }

    public function arhivedOrder($order_id){

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        $order = Orders::findOrFail($order_id);
        $order->archived = 1;
        $order->save();
        $order->touch();
        Event::fire(Route::currentRouteName(), array(array('title'=>'№'.getOrderNumber($order))));
        $json_request['responseText'] = 'Выполенено';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function deleteOrder($order_id){

        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        $order = Orders::findOrFail($order_id);
        Orders::findOrFail($order_id)->payment_numbers()->delete();
        if($orderListenersIDs = Orders::findOrFail($order_id)->listeners()->lists('id')):
            OrdersListenersTests::whereIn('order_listeners_id',$orderListenersIDs)->delete();
            Orders::findOrFail($order_id)->listeners()->delete();
        endif;
        Event::fire(Route::currentRouteName(), array(array('title'=>'№'.getOrderNumber($order))));
        $order->delete();
        $json_request['responseText'] = 'Выполенено';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function OrderPaymentNumberStore($order_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),OrderPayments::$rules);
            if($validator->passes()):
                if (OrderPayments::create(array('order_id'=>$order_id,'price'=>Input::get('price'),'payment_number'=>Input::get('payment_number'),'payment_date'=>date('Y-m-d', strtotime(Input::get('payment_date')))))):
                    self::autoChangeOrderStatus($order_id);
                    Event::fire(Route::currentRouteName(), array(array('title'=>'Заказ №'.Orders::findOrFail($order_id)->pluck('number').'. Сумма: '.Input::get('price').' руб.')));
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.success_insert');
                    $json_request['redirect'] = URL::route('moderator-order-extended',$order_id);
                    $json_request['status'] = TRUE;
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function OrderPaymentNumberUpdate($order_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),OrderPayments::$rules_update);
            if($validator->passes()):
                if ($order_payment = OrderPayments::where('id',Input::get('payment_order_id'))->first()):
                    $order_payment->payment_date = date('Y-m-d', strtotime(Input::get('payment_date')));
                    $order_payment->price = Input::get('price');
                    $order_payment->payment_number = Input::get('payment_number');
                    $order_payment->save();
                    $order_payment->touch();
                    self::autoChangeOrderStatus($order_id);
                    Event::fire(Route::currentRouteName(), array(array('title'=>'Заказ №'.Orders::findOrFail($order_id)->pluck('number').'. Сумма: '.Input::get('price').' руб.')));
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.success_save');
                    $json_request['redirect'] = URL::route('moderator-order-extended',$order_id);
                    $json_request['status'] = TRUE;
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function OrderPaymentNumberDelete($order_id,$payment_order_id){

        Event::fire(Route::currentRouteName(), array(array('title'=>'Заказ №'.Orders::findOrFail($order_id)->pluck('number').'. Сумма: '.OrderPayments::where('id',$payment_order_id)->where('order_id',$order_id)->pluck('price').' руб.')));
        OrderPayments::where('id',$payment_order_id)->where('order_id',$order_id)->delete();
        self::autoChangeOrderStatus($order_id);
        return Redirect::route('moderator-order-extended',$order_id);
    }

    public function changeOrderListenerAccess($order_id){

        $order_listener_id = 0;
        $json_request = array('status'=>FALSE,'responseText'=>'','responseOrderStatus'=> FALSE);
        if(Request::ajax() && Input::has('courses')):
            $courses = Input::get('courses');
            $ListenersStatuses = array();
            if (!empty($courses)):
                foreach ($courses as $value):
                    foreach($value as $course_id => $check):
                        $ListenersStatuses[$course_id] = $check;
                    endforeach;
                endforeach;
                if (!empty($ListenersStatuses)):
                    $now = date('Y-m-d H:i:s');
                    $order = Orders::where('id',$order_id)->with('organization','individual')->first();
                    foreach(OrderListeners::where('order_id',$order_id)->get() as $orderListener):
                        if ($orderListener->access_status == 0 && isset($ListenersStatuses[$orderListener->id]) && $ListenersStatuses[$orderListener->id] == 1):
                            $studyDays = !empty($orderListener->course->hours) ? floor($orderListener->course->hours/8): floor(Config::get('site.time_to_study_begin')/8);
                            if (!empty($order->organization)):
                                Event::fire('listener.study-access', array(array('accountID'=>$orderListener->user_id,'link'=>URL::to('listener/study/course/'.$orderListener->id.'-'.BaseController::stringTranslite($orderListener->course->title,100)),'course'=>$orderListener->course->code,'date'=>(new myDateTime())->setDateString($now)->addDays($studyDays)->format('d.m.Y'))));
                            elseif (!empty($order->individual)):
                                Event::fire('listener.study-access', array(array('accountID'=>$orderListener->user_id,'link'=>URL::to('individual-listener/study/course/'.$orderListener->id.'-'.BaseController::stringTranslite($orderListener->course->title,100)),'course'=>$orderListener->course->code,'date'=>(new myDateTime())->setDateString($now)->addDays($studyDays)->format('d.m.Y'))));
                            endif;
                            if ($order->study_status == 0):
                                Orders::where('id',$order_id)->update(array('study_status'=>1,'study_date'=>$now,'updated_at'=>$now));
                            endif;
                        endif;
                        OrderListeners::where('order_id',$order_id)->where('id',$orderListener->id)->update(array('access_status'=>$ListenersStatuses[$orderListener->id],'updated_at'=>$now));
                    endforeach;
                    $closeStudyStatus = TRUE;
                    if(OrderListeners::where('order_id',$order_id)->where('access_status',1)->exists()):
                        $closeStudyStatus = FALSE;
                    endif;
                    if ($closeStudyStatus):
                        Orders::where('id',$order_id)->update(array('study_status'=>0,'study_date'=>'000-00-00 00:00:00','updated_at'=>$now));
                    endif;
                endif;
                $json_request['status'] = TRUE;
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function autoChangeOrderStatus($order_id){

        if ($order = Orders::where('id',$order_id)->with('payment_numbers','listeners','organization','individual')->first()):
            $total_summa = 0;
            $payment_summa = 0;
            foreach($order->listeners as $listener):
                $total_summa += $listener->price;
            endforeach;
            foreach($order->payment_numbers as $payment_number):
                $payment_summa += $payment_number->price;
            endforeach;
            $now = date('Y-m-d H:i:s');
            if ($payment_summa >= $total_summa):
                Orders::where('id',$order_id)->update(array('payment_status'=>2,'payment_date'=>$now,'updated_at'=>$now));
                if (!empty($order->organization)):
                    Event::fire('organization.order.yes-puy-yes-access', array(array('accountID'=>$order->user_id,'link'=>URL::to('organization/order/'.$order->id),'order'=>getOrderNumber($order))));
                elseif (!empty($order->individual)):
                    Event::fire('individual.order.yes-puy-yes-access', array(array('accountID'=>$order->user_id,'link'=>URL::to('individual-listener/order/'.$order->id),'order'=>getOrderNumber($order))));
                endif;
            elseif($payment_summa > 0 && $payment_summa < $total_summa):
                if (!empty($order->organization)):
                    Event::fire('organization.order.part-puy-not-access', array(array('accountID'=>$order->user_id,'link'=>URL::to('organization/order/'.$order->id),'order'=>getOrderNumber($order))));
                elseif (!empty($order->individual)):
                    Event::fire('individual.order.part-puy-not-access', array(array('accountID'=>$order->user_id,'link'=>URL::to('individual-listener/order/'.$order->id),'order'=>getOrderNumber($order))));
                endif;
                Orders::where('id',$order_id)->update(array('payment_status'=>3,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
            else:
                Orders::where('id',$order_id)->update(array('payment_status'=>1,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
            endif;
        endif;
    }

    /****************************************************************************/
    /****************************** СЛУШАТЕЛИ ***********************************/
    /****************************************************************************/
    public function ListenersList(){

        $page_data = array(
            'page_title'=> 'Список слушателей',
            'page_description'=> '',
            'page_keywords'=> '',
            'listeners' => array()
        );
        $listeners = array();
        if ($companies_listeners = User_listener::orderBy('created_at','DESC')->with('organization')->get()):
            $listeners = array_merge($listeners,$companies_listeners->toArray());
        endif;
        if ($individual_listeners = User_individual::orderBy('created_at','DESC')->get()):
            $listeners = array_merge($listeners,$individual_listeners->toArray());
        endif;
        if (count($listeners)):
            foreach ($listeners as $key => $row):
                @$created_at[$key]  = $row['created_at'];
            endforeach;
            array_multisort($created_at, SORT_DESC, $listeners);
        endif;
        $page_data['listeners'] = $listeners;
        return View::make(Helper::acclayout('listeners'),$page_data);
    }

    public function ListenerProfile($listener_id){

        $page_data = array(
            'page_title'=> 'Профиль слушателя',
            'page_description'=> '',
            'page_keywords'=> '',
            'profile' => array()
        );

        if($account = User::where('id',$listener_id)->first()):
            if ($account->group_id == 5):
                $page_data['profile'] = User_listener::where('id',$listener_id)->with('organization')->first();
                $page_data['profile']['group_id'] = 5;
            elseif($account->group_id == 6):
                $page_data['profile'] = User_individual::where('id',$listener_id)->first();
                $page_data['profile']['group_id'] = 6;
            endif;
        endif;
        return View::make(Helper::acclayout('listener-profile'),$page_data);
    }

    public function ListenerProfileEdit($listener_id){

        $page_data = array(
            'page_title'=> 'Профиль слушателя',
            'page_description'=> '',
            'page_keywords'=> '',
            'profile' => array(),
            'profile_group_id' => 0
        );

        if($account = User::where('id',$listener_id)->first()):
            if ($account->group_id == 5):
                $page_data['profile'] = User_listener::where('id',$listener_id)->first();
                $page_data['profile_group_id'] = 5;
            elseif($account->group_id == 6):
                $page_data['profile'] = User_individual::where('id',$listener_id)->first();
                $page_data['profile_group_id'] = 6;
            endif;
        endif;
        return View::make(Helper::acclayout('listener-profile-edit'),$page_data);
    }

    public function ListenerProfileUpdate($listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $account = User::where('id',$listener_id)->first();
            if($account && $account->group_id == 5):
                $validator = Validator::make(Input::all(),Listener::$moderator_rules);
            elseif($account && $account->group_id == 6):
                $validator = Validator::make(Input::all(),Individual::$moderator_rules);
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
            endif;
            if($validator->passes()):
                if ($account->group_id == 5):
                    self::ListenerCompanyAccountUpdate($account,$listener_id,Input::all());
                    Event::fire(Route::currentRouteName(), array(array('title'=>'Слушатель: '.User_listener::where('id',$listener_id)->pluck('fio'))));
                elseif($account->group_id == 6):
                    self::ListenerIndividualAccountUpdate($account,$listener_id,Input::all());
                    Event::fire(Route::currentRouteName(), array(array('title'=>'Слушатель: '.User_individual::where('id',$listener_id)->pluck('fio'))));
                endif;
                $json_request['status'] = TRUE;
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.success');
                $json_request['redirect'] = URL::route('moderator-listener-profile',$listener_id);
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function ListenerCompanyAccountUpdate($user,$listener_id,$post){

        if($listener = Listener::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['fio']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->email = $post['email'];
            if($post['active'] == 1 && ($user->active == 0 || $user->active == 2) ):
                Event::fire('moderator-listener-profile-activated', array(array('title'=>$post['fio'])));
            endif;
            $user->active = $post['active'];
            if($post['active'] == 1):
                $user->temporary_code = '';
                $user->code_life = 0;
            endif;
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
        return FALSE;
    }

    private function ListenerIndividualAccountUpdate($user,$listener_id,$post){

        if($individual = Individual::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['fio']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->email = $post['email'];
            if($post['active'] == 1 && ($user->active == 0 || $user->active == 2) ):
                Event::fire('moderator-listener-profile-activated', array(array('title'=>$post['fio'])));
            endif;
            $user->active = $post['active'];
            if($post['active'] == 1):
                $user->temporary_code = '';
                $user->code_life = 0;
            endif;
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

            $individual->discount = $post['discount'];
            if($individual->moderator_approve == 0 && $post['moderator_approve'] == 1):
                Event::fire('moderator-company-profile-approved', array(array('title'=>$post['title'])));
                Event::fire('account.approved-profile',array(array('accountID'=>$user->id)));
            endif;
            $individual->moderator_approve = $post['moderator_approve'];

            $individual->save();
            $individual->touch();

            return TRUE;
        endif;
        return FALSE;
    }
    /****************************************************************************/
    /***************************** СТАТИСТИКА ***********************************/
    /****************************************************************************/
    public function statistic(){

        $period_begin = date("d.m.Y",(strtotime('first day of this month', time())));
        $period_end = date("d.m.Y");
        if(Session::has('period_begin')):
            $period_begin = date('Y-m-d 00:00:00',strtotime(Session::get('period_begin')));
        endif;
        if(Session::has('period_begin')):
            $period_end = date('Y-m-d 23:59:59',strtotime(Session::get('period_end')));
        endif;
        $users = array(); $index = 0;
        $closedOrdersUsersIDs = Orders::where('close_status',1)
            ->where('completed',1)
            ->where('close_date','>=',$period_begin)
            ->where('close_date','<=',$period_end)
            ->lists('user_id');
        if ($closedOrdersUsersIDs):
            $organizationsLists = User_organization::orderBy('created_at','DESC')
                ->whereIn('id',$closedOrdersUsersIDs)
                ->with(array('orders'=>function($query) {
                    $query->where('close_status', 0);
                    $query->with('payment_numbers');
                }))->get();
            if ($organizationsLists):
                foreach($organizationsLists as $company):
                    $users[$index]['group'] = 4;
                    $users[$index]['id'] = $company->id;
                    $users[$index]['title'] = $company->title;
                    $users[$index]['created_at'] = $company->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y");
                    $users[$index]['manager'] = $company->manager;
                    $users[$index]['fio_manager'] = $company->fio_manager;
                    $users[$index]['email'] = $company->email;
                    $users[$index]['phone'] = $company->phone;
                    $users[$index]['orders_count'] = $company->orders->count();
                    $users[$index]['discount'] = $company->discount;
                    $users[$index]['real_earnings'] = 0;
                    if ($company->orders->count()):
                        foreach ($company->orders as $order):
                            if ($order->payment_numbers->count()):
                                foreach ($order->payment_numbers as $payment_number):
                                    $users[$index]['real_earnings'] += $payment_number->price;
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    $index++;
                endforeach;
            endif;
            $individualsLists = User_individual::orderBy('created_at','DESC')
                ->whereIn('id',$closedOrdersUsersIDs)
                ->with(array('orders'=>function($query) {
                    $query->where('close_status', 0);
                    $query->with('payment_numbers');
                }))->get();
            if ($individualsLists):
                foreach($individualsLists as $individual):
                    $users[$index]['group'] = 6;
                    $users[$index]['id'] = $individual->id;
                    $users[$index]['title'] = $individual->fio;
                    $users[$index]['created_at'] = $individual->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y");
                    $users[$index]['manager'] = $individual->position;
                    $users[$index]['fio_manager'] = '';
                    $users[$index]['email'] = $individual->email;
                    $users[$index]['phone'] = $individual->phone;
                    $users[$index]['orders_count'] = $individual->orders->count();
                    $users[$index]['discount'] = $individual->discount;
                    $users[$index]['real_earnings'] = 0;
                    if ($company->orders->count()):
                        foreach ($individual->orders as $order):
                            if ($order->payment_numbers->count()):
                                foreach ($order->payment_numbers as $payment_number):
                                    $users[$index]['real_earnings'] += $payment_number->price;
                                endforeach;
                            endif;
                        endforeach;
                    endif;
                    $index++;
                endforeach;
            endif;
        endif;

        $closedOrdersIDs = Orders::where('close_status',1)
            ->where('completed',1)
            ->where('close_date','>=',$period_begin)
            ->where('close_date','<=',$period_end)
            ->lists('id');
        $courses = array();
        if ($closedOrdersIDs && $coursesLists = OrderListeners::where('order_id',$closedOrdersIDs)->with('course')->get()):
            foreach($coursesLists as $course):
                $courses[$course->course->id]['code'] = $course->course->code;
                $courses[$course->course->id]['title'] = $course->course->title;
                $courses[$course->course->id]['price'] = $course->course->price;
                $courses[$course->course->id]['discount'] = $course->course->discount;
                $courses[$course->course->id]['real_earnings'] = 0;
            endforeach;
            foreach($coursesLists as $course):
                $courses[$course->course->id]['real_earnings'] += $course->price;
            endforeach;
        endif;
        $page_data = array(
            'page_title'=> 'Статистика',
            'page_description'=> '',
            'page_keywords'=> '',
            'period_begin' => date('d.m.Y',strtotime($period_begin)),
            'period_end' => date('d.m.Y',strtotime($period_end)),
            'users' => $users,
            'courses' => $courses,
        );
        return View::make(Helper::acclayout('statistic'),$page_data);
    }

    public function statisticSetPeriod(){

        return Redirect::route('moderator-statistic')->with('period_begin',Input::get('period_begin'))->with('period_end',Input::get('period_end'));
    }
}