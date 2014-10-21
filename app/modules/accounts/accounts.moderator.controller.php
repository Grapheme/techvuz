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
                Route::post('order/{order_id}/extended/set-access/{order_listener_id}', array('as' => 'order-listener-access', 'uses' => $class . '@changeOrderListenerAccess'));
                Route::post('order/{order_id}/extended/set-status', array('as' => 'change-order-status', 'uses' => $class . '@changeOrderStatus'));

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
                $companies[$index]['created_at'] = $company->created_at->format("d.m.Y");
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
            $user->active = $post['active'];
            if($post['active'] == 1):
                $user->temporary_code = '';
                $user->code_life = 0;
            endif;
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
            $organization->discount = $post['discount'];
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
        if($page_data['order'] = Orders::where('id',$order_id)->with('payment_numbers')->first()):
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

    public function OrderPaymentNumberStore($order_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),OrderPayments::$rules);
            if($validator->passes()):
                if (OrderPayments::create(array('order_id'=>$order_id,'price'=>Input::get('price'),'payment_number'=>Input::get('payment_number'),'payment_date'=>date('Y-m-d', strtotime(Input::get('payment_date')))))):
                    self::authChangeOrderStatus($order_id);
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
                    self::authChangeOrderStatus($order_id);
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

        OrderPayments::where('id',$payment_order_id)->where('order_id',$order_id)->delete();
        self::authChangeOrderStatus($order_id);
        return Redirect::route('moderator-order-extended',$order_id);
    }

    public function changeOrderListenerAccess($order_id,$order_listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseOrderStatus'=> FALSE);
        if(Request::ajax()):
            if($orderListener = OrderListeners::where('id',$order_listener_id)->where('order_id',$order_id)->first()):
                if ($orderListener->access_status == 0):
                    $orderListener->access_status = 1;
                else:
                    $orderListener->access_status = 0;
                endif;
                $orderListener->save();
                $orderListener->touch();
                $order = Orders::where('id',$order_id)->first();
                $countAccess = 0; $total_count = 0;
                foreach(OrderListeners::where('order_id',$order_id)->get() as $order_listener):
                    if ($order_listener->access_status == 1):
                        $countAccess++;
                    endif;
                    $total_count++;
                endforeach;
                $now = date('Y-m-d H:i:s');
                if ($order->payment_status == 1 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>5,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 5;
                elseif ($order->payment_status == 6 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>2,'payment_date'=>$now->getTimestamp(),'updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 2;
                elseif ($order->payment_status == 3 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>4,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 4;
                elseif($order->payment_status == 2 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>6,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 6;
                elseif($order->payment_status == 4 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>3,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 3;
                elseif($order->payment_status == 5 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>1,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>$now));
                    $json_request['responseOrderStatus'] = 1;
                endif;

                $json_request['status'] = TRUE;
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    public function changeOrderStatus($order_id){

        $json_request = array('status'=>FALSE,'responseText'=>'');
        if(Request::ajax() && Input::get('status')):
            if ($order = Orders::where('id',$order_id)->first()):
                $order->payment_status = Input::get('status');
                $order->payment_date = '0000-00-0000:00:00';
                $now = date('Y-m-d H:i:s');
                switch(Input::get('status')):
                    case 2: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>$now));
                        $order->payment_date = $now;
                        break;
                    case 4: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>$now));
                        $order->payment_date = $now;
                        break;
                    case 5: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>$now));
                        break;
                    case 6: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>0,'updated_at'=>$now));
                        $order->payment_date = $now;
                        break;
                endswitch;
                $order->save();
                $order->touch();
                Event::listen('order.payment', function ($data) { });
                $json_request['responseText'] = Lang::get('interface.DEFAULT.success_change');
                $json_request['status'] = TRUE;
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
    }

    private function authChangeOrderStatus($order_id){

        if ($order = Orders::where('id',$order_id)->with('payment_numbers','listeners')->first()):
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
                Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>$now));
            elseif($payment_summa > 0 && $payment_summa < $total_summa):
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
            array_multisort($created_at, SORT_ASC, $listeners);
            $page_data['listeners'] = $listeners;
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
            if ($account->group_id = 5):
                $page_data['profile'] = User_listener::where('id',$listener_id)->first();
            elseif($account->group_id = 6):
                $page_data['profile'] = User_individual::where('id',$listener_id)->first();
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
            if ($account->group_id = 5):
                $page_data['profile'] = User_listener::where('id',$listener_id)->first();
                $page_data['profile_group_id'] = 5;
            elseif($account->group_id = 6):
                $page_data['profile'] = User_individual::where('id',$listener_id)->first();
                $page_data['profile_group_id'] = 6;
            endif;
        endif;
        return View::make(Helper::acclayout('listener-profile-edit'),$page_data);
    }

    public function ListenerProfileUpdate($listener_id){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),Listener::$moderator_rules);
            if($validator->passes()):
                if($account = User::where('id',$listener_id)->first()):
                    if ($account->group_id = 5):
                        self::ListenerCompanyAccountUpdate($account,$listener_id,Input::all());
                    elseif($account->group_id = 6):
                        self::ListenerIndividualAccountUpdate($account,$listener_id,Input::all());
                    endif;
                    $json_request['status'] = TRUE;
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.success');
                    $json_request['redirect'] = URL::route('moderator-listener-profile',$listener_id);
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

    private function ListenerCompanyAccountUpdate($user,$listener_id,$post){

        if($listener = Listener::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['fio']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
            $user->email = $post['email'];
            $user->active = $post['active'];
            if($post['active'] == 1):
                $user->temporary_code = '';
                $user->code_life = 0;
            endif;
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
    }

    private function ListenerIndividualAccountUpdate($user,$listener_id,$post){

        return TRUE;
    }
    /****************************************************************************/
    /***************************** СТАТИСТИКА ***********************************/
    /****************************************************************************/
    public function statistic(){

        $period_begin = date("d.m.Y",(strtotime('first day of this month', time())));
        $period_end = date("d.m.Y");
        if(Session::has('period_begin')):
            $period_begin = Session::get('period_begin');
        endif;
        if(Session::has('period_begin')):
            $period_end = Session::get('period_end');
        endif;
        $users = array(); $index = 0;
        $closedOrdersUsersIDs = Orders::where('close_status',0)
            ->where('completed',1)
            ->where('close_date','>=',$period_begin.' 00:00:00')
            ->where('close_date','<=',$period_end.' 23:59:59')
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
                    $users[$index]['created_at'] = $company->created_at->format("d.m.Y");
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
                    $users[$index]['created_at'] = $individual->created_at->format("d.m.Y");
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

        $closedOrdersIDs = Orders::where('close_status',0)
            ->where('completed',1)
            ->where('close_date','>=',$period_begin.' 00:00:00')
            ->where('close_date','<=',$period_end.' 23:59:59')
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
            'period_begin' => $period_begin,
            'period_end' => $period_end,
            'users' => $users,
            'courses' => $courses,
        );
        return View::make(Helper::acclayout('statistic'),$page_data);
    }

    public function statisticSetPeriod(){

        return Redirect::route('moderator-statistic')->with('period_begin',Input::get('period_begin'))->with('period_end',Input::get('period_end'));
    }
}