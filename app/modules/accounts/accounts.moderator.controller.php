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
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function () use ($class) {
                Route::get('without-statistic', array('as' => 'moderator-account-without-statistic', 'uses' => $class . '@withoutStatisticList'));

                Route::get('companies', array('as' => 'moderator-companies-list', 'uses' => $class . '@CompaniesList'));
                Route::get('companies/profile/{company_id}', array('as' => 'moderator-company-profile',
                    'uses' => $class . '@CompanyProfile'));
                Route::get('companies/profile/{company_id}/edit', array('as' => 'moderator-company-profile-edit',
                    'uses' => $class . '@CompanyProfileEdit'));
                Route::patch('companies/profile/{company_id}/update', array('as' => 'moderator-company-profile-update',
                    'uses' => $class . '@CompanyProfileUpdate'));

                Route::get('orders', array('as' => 'moderator-orders-list', 'uses' => $class . '@OrdersList'));
                Route::get('order/{order_id}/extended', array('as' => 'moderator-order-extended',
                    'uses' => $class . '@OrderExtendedView'));
                Route::post('order/{order_id}/extended/set-access', array('as' => 'order-listener-access',
                    'uses' => $class . '@changeOrderListenerAccess'));
                Route::post('order/{order_id}/extended/set-status', array('as' => 'change-order-status',
                    'uses' => $class . '@changeOrderStatus'));
                Route::get('order/{order_id}/edit', array('as' => 'moderator-order-edit',
                    'uses' => $class . '@OrderEdit'));
                Route::patch('order/{order_id}/update', array('as' => 'moderator-order-update',
                    'uses' => $class . '@OrderUpdate'));

                Route::post('order/{order_id}/arhived', array('as' => 'moderator-order-arhived',
                    'uses' => $class . '@arhivedOrder'));
                Route::delete('order/{order_id}/delete', array('as' => 'moderator-order-delete',
                    'uses' => $class . '@deleteOrder'));

                Route::post('order/{order_id}/payment-number/store', array('before' => 'csrf',
                    'as' => 'payment-order-number-store', 'uses' => $class . '@OrderPaymentNumberStore'));
                Route::patch('order/{order_id}/payment-number/update', array('before' => 'csrf',
                    'as' => 'payment-order-number-update', 'uses' => $class . '@OrderPaymentNumberUpdate'));
                Route::delete('order/{order_id}/payment-number/delete/{payment_order_id}', array('before' => 'csrf',
                    'as' => 'payment-order-number-delete', 'uses' => $class . '@OrderPaymentNumberDelete'));

                Route::get('listeners', array('as' => 'moderator-listeners-list', 'uses' => $class . '@ListenersList'));
                Route::get('listeners/profile/{listener_id}', array('as' => 'moderator-listener-profile',
                    'uses' => $class . '@ListenerProfile'));
                Route::get('listeners/profile/{listener_id}/edit', array('as' => 'moderator-listener-profile-edit',
                    'uses' => $class . '@ListenerProfileEdit'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf',
                    'as' => 'moderator-listener-profile-update', 'uses' => $class . '@ListenerProfileUpdate'));
                Route::patch('listeners/profile/{listener_id}/update', array('before' => 'csrf',
                    'as' => 'moderator-listener-profile-update', 'uses' => $class . '@ListenerProfileUpdate'));

                Route::get('statistic', array('as' => 'moderator-statistic', 'uses' => $class . '@statistic'));
                Route::post('statistic-set-period', array('as' => 'moderator-statistic-set-period',
                    'uses' => $class . '@statisticSetPeriod'));
                Route::post('statistic-extend-request', array('as' => 'moderator-statistic-extend-request',
                    'uses' => $class . '@statisticExtendRequest'));

                Route::get('notifications', array('as' => 'moderator-notifications',
                    'uses' => $class . '@NotificationsList'));
                Route::delete('notification/{notification_id}/delete', array('as' => 'moderator-notification-delete',
                    'uses' => $class . '@NotificationDelete'));
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
    public function __construct() {

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

    public function NotificationsList() {

        $page_data = array(
            'page_title' => 'Системные сообщения',
            'page_description' => '',
            'page_keywords' => '',
        );
        return View::make(Helper::acclayout('notifications'), $page_data);
    }

    public function NotificationDelete($notification_id) {

        if ($notification_id == 'all'):
            $messages = Dictionary::valuesBySlug('system-messages', function ($query) {
                $query->filter_by_field('user_id', '=', 0);
            });
            foreach ($messages as $message):
                if ($IDs = array_keys(modifyKeys($message->fields, 'id'))):
                    DicFieldVal::whereIn('id', $IDs)->delete();
                endif;
            endforeach;
            if ($IDs = array_keys(modifyKeys($messages, 'id'))):
                DicFieldVal::whereIn('id', $IDs)->delete();
            endif;
        elseif ($notification_id == 'selected' && Input::has('messages')):
            $notificationIDs = Input::get('messages');
            $messages = Dictionary::valuesBySlug('system-messages', function ($query) use ($notificationIDs) {
                $query->whereIn('dictionary_values.id', $notificationIDs);
                $query->filter_by_field('user_id', '=', 0);
            });
            foreach ($messages as $message):
                if ($IDs = array_keys(modifyKeys($message->fields, 'id'))):
                    DicFieldVal::whereIn('id', $IDs)->delete();
                endif;
            endforeach;
            if ($IDs = array_keys(modifyKeys($messages, 'id'))):
                DicFieldVal::whereIn('id', $IDs)->delete();
            endif;
        else:
            DicVal::where('id', $notification_id)->delete();
            DicFieldVal::where('dicval_id', $notification_id)->delete();
        endif;
        return Redirect::back();
    }
    /****************************************************************************/
    /******************************* КОМПАНИИ ***********************************/
    /****************************************************************************/
    public function CompaniesList() {

        $page_data = array(
            'page_title' => 'Список компаний',
            'page_description' => '',
            'page_keywords' => '',
            'companies' => array()
        );
        if ($companies_list = User_organization::orderBy('created_at', 'DESC')->with('orders.listeners', 'orders.payment_numbers')->get()):
            $companies = array();
            foreach ($companies_list as $index => $company):
                $companies[$index]['id'] = $company->id;
                $companies[$index]['title'] = $company->title;
                $companies[$index]['created_at'] = $company->created_at->timezone(Config::get('site.time_zone'));
                $companies[$index]['manager'] = $company->manager;
                $companies[$index]['fio_manager'] = $company->fio_manager;
                $companies[$index]['email'] = $company->email;
                $companies[$index]['orders_count'] = $company->orders->count();
                $companies[$index]['discount'] = $company->discount;
                $companies[$index]['orders_earnings'] = array('total_earnings' => 0, 'real_earnings' => 0);
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
        return View::make(Helper::acclayout('companies'), $page_data);
    }

    public function CompanyProfile($company_id) {

        $page_data = array(
            'page_title' => 'Просмотр профиля компании',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array(),
            'listeners' => array(),
            'orders' => array(),

        );
        if ($page_data['profile'] = User_organization::where('id', $company_id)->first()):
            $page_data['listeners'] = User_listener::where('organization_id', $company_id)->orderBy('created_at', 'DESC')->get();
            $page_data['orders'] = Orders::where('user_id', $company_id)->with('payment')->with('listeners')->get();
            return View::make(Helper::acclayout('company-profile'), $page_data);
        else:
            App::abort(404);
        endif;
    }

    public function CompanyProfileEdit($company_id) {

        $page_data = array(
            'page_title' => 'Редактирование профиля компании',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array(),
        );
        if ($page_data['profile'] = User_organization::where('id', $company_id)->first()):
            return View::make(Helper::acclayout('company-profile-edit'), $page_data);
        else:
            App::abort(404);
        endif;
    }

    public function CompanyProfileUpdate($company_id) {

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), Organization::$moderator_rules);
            if ($validator->passes()):
                if (self::CompanyAccountUpdate($company_id, Input::all())):
                    Event::fire(Route::currentRouteName(), array(array('title' => 'Организация: ' . User_organization::where('id', $company_id)->pluck('title'))));
                    $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_COMPANY.success');
                    $json_request['redirect'] = URL::route('moderator-company-profile', $company_id);
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
        return Response::json($json_request, 200);
    }

    private function CompanyAccountUpdate($company_id, $post) {

        $user = User::findOrFail($company_id);
        if ($organization = Organization::where('user_id', $user->id)->first()):
            $fio = explode(' ', $post['name']);
            $user->name = (isset($fio[1])) ? $fio[1] : '';
            $user->surname = (isset($fio[0])) ? $fio[0] : '';
            $user->email = $post['email'];
            if ($post['active'] == 1 && ($user->active == 0 || $user->active == 2)):
                Event::fire('moderator-company-profile-activated', array(array('title' => $post['title'])));
            endif;
            $user->active = $post['active'];
            if ($post['active'] == 1):
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
            if ($organization->moderator_approve == 0 && $post['moderator_approve'] == 1):
                Event::fire('moderator-company-profile-approved', array(array('title' => $post['title'])));
                Event::fire('account.approved-profile', array(array('accountID' => $user->id)));
            endif;
            $organization->moderator_approve = $post['moderator_approve'];
            $organization->statistic = $post['statistic'];
            Orders::where('user_id', $user->id)->update(array('statistic' => $post['statistic']));
            $organization->save();
            $organization->touch();

            return TRUE;
        endif;
    }
    /****************************************************************************/
    /******************************** ЗАКАЗЫ ************************************/
    /****************************************************************************/
    public function OrdersList() {

        $page_data = array(
            'page_title' => 'Список заказов',
            'page_description' => '',
            'page_keywords' => '',
        );
        return View::make(Helper::acclayout('orders'), $page_data);
    }

    public function OrderExtendedView($order_id) {

        $page_data = array(
            'page_title' => 'Просмотр заказа',
            'page_description' => '',
            'page_keywords' => '',
            'order' => array(),
            'courses' => array()
        );
        if ($page_data['order'] = Orders::where('id', $order_id)->with('payment_numbers', 'organization', 'individual')->first()):
            $courses = array();
            foreach (Orders::where('id', $order_id)->first()->listeners()->with('user_listener', 'course')->get() as $course):
                $courses[$course->course_id]['course']['code'] = $course->course->code;
                $courses[$course->course_id]['course']['title'] = $course->course->title;
                $courses[$course->course_id]['course']['description'] = $course->course->description;
                $courses[$course->course_id]['course']['price'] = $course->course->price;
                $courses[$course->course_id]['course']['hours'] = $course->course->hours;
                $courses[$course->course_id]['listeners'][] = $course;
            endforeach;
            $page_data['courses'] = $courses;
            return View::make(Helper::acclayout('order'), $page_data);
        else:
            App::abort(404);
        endif;

    }

    public function OrderEdit($order_id) {

        $page_data = array(
            'page_title' => 'Редактирование заказа',
            'page_description' => '',
            'page_keywords' => '',
            'order' => Orders::where('id', $order_id)->with('contract', 'invoice', 'act')->first()
        );
        return View::make(Helper::acclayout('order-edit'), $page_data);
    }

    public function OrderUpdate($order_id) {

        if (!Request::ajax()) return App::abort(404);
        $json_request = array('status' => FALSE, 'responseText' => '');
        if ($order = Orders::where('id', $order_id)->first()):
            $date = new myDateTime();
            $order->number = (int)Input::get('number');
            $order->created_at = $date->setDateString(Input::get('created_at'))->format('Y-m-d H:i:s');
            #$order->payment_date = $date->setDateString(Input::get('payment_date'))->format('Y-m-d H:i:s');
            $order->study_date = $date->setDateString(Input::get('study_date'))->format('Y-m-d H:i:s');
            if (Input::get('study_date') == ''):
                $order->study_status = 0;
            else:
                $order->study_status = 1;
            endif;
            $order->contract_id = ExtForm::process('upload', @Input::all()['contract']);
            $order->invoice_id = ExtForm::process('upload', @Input::all()['invoice']);
            $order->act_id = ExtForm::process('upload', @Input::all()['act']);
            $order->save();
            $order->touch();
            Event::fire(Route::currentRouteName(), array(array('title' => '№' . getOrderNumber($order))));
            $json_request['responseText'] = 'Выполенено';
            $json_request['status'] = TRUE;
            $json_request['redirect'] = URL::route('moderator-order-extended', $order_id);
        endif;
        return Response::json($json_request, 200);
    }

    public function arhivedOrder($order_id) {

        if (!Request::ajax()) return App::abort(404);
        $json_request = array('status' => FALSE, 'responseText' => '');
        $order = Orders::findOrFail($order_id);
        $order->archived = Input::get('archived');
        $order->save();
        $order->touch();
        Event::fire(Route::currentRouteName(), array(array('title' => '№' . getOrderNumber($order))));
        $json_request['responseText'] = 'Выполенено';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function deleteOrder($order_id) {

        if (!Request::ajax()) return App::abort(404);
        $json_request = array('status' => FALSE, 'responseText' => '');
        $order = Orders::findOrFail($order_id);

        $group_id = User::where('id', $order->user_id)->pluck('group_id');
        $zak_link = $zak_name = '';
        if ($group_id == 4):
            $zak_link = URL::to('moderator/companies/profile/' . $order->user_id);
            $zak_name = User_organization::where('id', $order->user_id)->pluck('title');
        elseif ($group_id == 6):
            $zak_link = URL::to('moderator/listeners/profile/' . $order->user_id);
            $zak_name = User_individual::where('id', $order->user_id)->pluck('fio');
        endif;

        Event::fire('moderator.delete.order', array(array('accountID' => 0,
            'order' => getOrderNumber($order),
            'organization_link' => $zak_link,
            'organization' => $zak_name)));

        Orders::findOrFail($order_id)->payment_numbers()->delete();
        if ($orderListenersIDs = Orders::findOrFail($order_id)->listeners()->lists('id')):
            OrdersListenersTests::whereIn('order_listeners_id', $orderListenersIDs)->delete();
            Orders::findOrFail($order_id)->listeners()->delete();
        endif;
        Event::fire(Route::currentRouteName(), array(array('title' => '№' . getOrderNumber($order))));
        $order->delete();
        $json_request['responseText'] = 'Выполенено';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function OrderPaymentNumberStore($order_id) {

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), OrderPayments::$rules);
            if ($validator->passes()):
                if (OrderPayments::create(array('order_id' => $order_id, 'price' => Input::get('price'),
                    'payment_number' => Input::get('payment_number'),
                    'payment_date' => date('Y-m-d', strtotime(Input::get('payment_date')))))
                ):
                    self::autoChangeOrderStatus($order_id);
                    Event::fire(Route::currentRouteName(), array(array('title' => 'Заказ №' . Orders::findOrFail($order_id)->pluck('number') . '. Сумма: ' . Input::get('price') . ' руб.')));
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.success_insert');
                    $json_request['redirect'] = URL::route('moderator-order-extended', $order_id);
                    $json_request['status'] = TRUE;
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function OrderPaymentNumberUpdate($order_id) {

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $validator = Validator::make(Input::all(), OrderPayments::$rules_update);
            if ($validator->passes()):
                if ($order_payment = OrderPayments::where('id', Input::get('payment_order_id'))->first()):
                    $order_payment->payment_date = date('Y-m-d', strtotime(Input::get('payment_date')));
                    $order_payment->price = Input::get('price');
                    $order_payment->payment_number = Input::get('payment_number');
                    $order_payment->save();
                    $order_payment->touch();
                    self::autoChangeOrderStatus($order_id);
                    Event::fire(Route::currentRouteName(), array(array('title' => 'Заказ №' . Orders::findOrFail($order_id)->pluck('number') . '. Сумма: ' . Input::get('price') . ' руб.')));
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.success_save');
                    $json_request['redirect'] = URL::route('moderator-order-extended', $order_id);
                    $json_request['status'] = TRUE;
                endif;
            else:
                $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    public function OrderPaymentNumberDelete($order_id, $payment_order_id) {

        Event::fire(Route::currentRouteName(), array(array('title' => 'Заказ №' . Orders::findOrFail($order_id)->pluck('number') . '. Сумма: ' . OrderPayments::where('id', $payment_order_id)->where('order_id', $order_id)->pluck('price') . ' руб.')));
        OrderPayments::where('id', $payment_order_id)->where('order_id', $order_id)->delete();
        self::autoChangeOrderStatus($order_id);
        return Redirect::route('moderator-order-extended', $order_id);
    }

    public function changeOrderListenerAccess($order_id) {

        $order_listener_id = 0;
        $json_request = array('status' => FALSE, 'responseText' => '', 'responseOrderStatus' => FALSE);
        if (Request::ajax() && Input::has('courses')):
            $courses = Input::get('courses');
            $ListenersStatuses = array();
            if (!empty($courses)):
                foreach ($courses as $value):
                    foreach ($value as $course_id => $check):
                        $ListenersStatuses[$course_id] = $check;
                    endforeach;
                endforeach;
                if (!empty($ListenersStatuses)):
                    $now = date('Y-m-d H:i:s');
                    $order = Orders::where('id', $order_id)->with('organization', 'individual')->first();
                    $listenerIDs = array();
                    foreach (OrderListeners::where('order_id', $order_id)->with('user_listener', 'user_individual')->get() as $index => $orderListener):
                        if ($orderListener->access_status == 0 && isset($ListenersStatuses[$orderListener->id]) && $ListenersStatuses[$orderListener->id] == 1):
                            $studyDays = !empty($orderListener->course->hours) ? floor($orderListener->course->hours / 8) : floor(Config::get('site.time_to_study_begin') / 8);
                            if (!empty($order->organization)):
                                $listenerIDs[$index] = array('accountID' => $orderListener->user_id,
                                    'listener' => $orderListener->user_listener->fio,
                                    'link' => URL::to('company/listeners/profile/' . $orderListener->user_id),
                                    'course' => $orderListener->course->code);
                                Event::fire('listener.study-access', array(array('accountID' => $orderListener->user_id,
                                    'link' => URL::to('listener/study/course/' . $orderListener->id . '-' . BaseController::stringTranslite($orderListener->course->title, 100)),
                                    'course' => $orderListener->course->code,
                                    'date' => (new myDateTime())->setDateString($now)->addDays($studyDays)->format('d.m.Y'))));
                            elseif (!empty($order->individual)):
                                Event::fire('listener.study-access', array(array('accountID' => $orderListener->user_id,
                                    'link' => URL::to('individual-listener/study/course/' . $orderListener->id . '-' . BaseController::stringTranslite($orderListener->course->title, 100)),
                                    'course' => $orderListener->course->code,
                                    'date' => (new myDateTime())->setDateString($now)->addDays($studyDays)->format('d.m.Y'))));
                            endif;
                            if ($order->study_status == 0):
                                $lastOrderEnrollmentNumber = (new Orders)->getLastOrderEnrollmentNumber(true);
                                Orders::where('id', $order_id)->update(array('number_enrollment' => $lastOrderEnrollmentNumber,
                                    'study_status' => 1, 'study_date' => $now, 'updated_at' => $now));
                            endif;
                        endif;
                        OrderListeners::where('order_id', $order_id)->where('id', $orderListener->id)->update(array('access_status' => $ListenersStatuses[$orderListener->id],
                            'updated_at' => $now));
                    endforeach;
                    if (!empty($listenerIDs)):
                        AccountsMessagesController::listenerStudyAccess('organization.study-access', $listenerIDs, $order->user_id);
                    endif;
                    $closeStudyStatus = TRUE;
                    if (OrderListeners::where('order_id', $order_id)->where('access_status', 1)->exists()):
                        $closeStudyStatus = FALSE;
                    endif;
                    if ($closeStudyStatus):
                        Orders::where('id', $order_id)->update(array('study_status' => 0,
                            'study_date' => '000-00-00 00:00:00', 'updated_at' => $now));
                    endif;
                endif;
                $json_request['status'] = TRUE;
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    private function autoChangeOrderStatus($order_id) {

        if ($order = Orders::where('id', $order_id)->with('payment_numbers', 'listeners', 'organization', 'individual')->first()):
            $total_summa = 0;
            $payment_summa = 0;
            foreach ($order->listeners as $listener):
                $total_summa += $listener->price;
            endforeach;
            foreach ($order->payment_numbers as $payment_number):
                $payment_summa += $payment_number->price;
            endforeach;
            $now = date('Y-m-d H:i:s');
            if ($payment_summa >= $total_summa):
                Orders::where('id', $order_id)->update(array('payment_status' => 2, 'payment_date' => $now,
                    'updated_at' => $now));
                if (!empty($order->organization)):
                    Event::fire('organization.order.yes-puy-yes-access', array(array('accountID' => $order->user_id,
                        'link' => URL::to('company/order/' . $order->id), 'order' => getOrderNumber($order))));
                elseif (!empty($order->individual)):
                    Event::fire('individual.order.yes-puy-yes-access', array(array('accountID' => $order->user_id,
                        'link' => URL::to('individual-listener/order/' . $order->id),
                        'order' => getOrderNumber($order))));
                endif;
            elseif ($payment_summa > 0 && $payment_summa < $total_summa):
                if (!empty($order->organization)):
                    Event::fire('organization.order.part-puy-not-access', array(array('accountID' => $order->user_id,
                        'link' => URL::to('company/order/' . $order->id), 'order' => getOrderNumber($order))));
                elseif (!empty($order->individual)):
                    Event::fire('individual.order.part-puy-not-access', array(array('accountID' => $order->user_id,
                        'link' => URL::to('individual-listener/order/' . $order->id),
                        'order' => getOrderNumber($order))));
                endif;
                Orders::where('id', $order_id)->update(array('payment_status' => 3,
                    'payment_date' => '0000-00-00 00:00:00', 'updated_at' => $now));
            else:
                Orders::where('id', $order_id)->update(array('payment_status' => 1,
                    'payment_date' => '0000-00-00 00:00:00', 'updated_at' => $now));
            endif;
        endif;
    }

    /****************************************************************************/

    public function withoutStatisticList(){

        $page_data = array(
            'page_title' => 'Список тестовых аккаунтов',
            'page_description' => '',
            'page_keywords' => '',
            'companies' => array(),
            'listeners' => array()
        );
        $page_data['companies'] = User_organization::where('statistic', 0)->get();
        $page_data['listeners'] = User_individual::where('statistic', 0)->get();
        return View::make(Helper::acclayout('without-statistic'), $page_data);
    }

    /****************************************************************************/
    /****************************** СЛУШАТЕЛИ ***********************************/
    /****************************************************************************/
    public function ListenersList() {

        $page_data = array(
            'page_title' => 'Список слушателей',
            'page_description' => '',
            'page_keywords' => '',
            'listeners' => array()
        );
        $listeners = array();
        if ($companies_listeners = User_listener::orderBy('created_at', 'DESC')->with('organization')->get()):
            $listeners = array_merge($listeners, $companies_listeners->toArray());
        endif;
        $individual_listeners = array();
        if ($individual_listeners_list = User_individual::orderBy('created_at', 'DESC')->with('orders.payment_numbers')->get()):
            foreach($individual_listeners_list as $index => $listener):
                $individual_listeners[$index]['id'] = $listener->id;
                $individual_listeners[$index]['fio'] = $listener->fio;
                $individual_listeners[$index]['email'] = $listener->email;
                $individual_listeners[$index]['created_at'] = $listener->created_at->timezone(Config::get('site.time_zone'));
                $individual_listeners[$index]['phone'] = $listener->phone;
                $individual_listeners[$index]['orders_count'] = count($listener->orders);
                $individual_listeners[$index]['discount'] = $listener->discount;
                $individual_listeners[$index]['orders_earnings'] = array('total_earnings' => 0, 'real_earnings' => 0);
                if ($listener->orders->count()):
                    foreach ($listener->orders as $order):
                        if ($order->listeners->count()):
                            foreach ($order->listeners as $listener):
                                $individual_listeners[$index]['orders_earnings']['total_earnings'] += $listener->price;
                            endforeach;
                        endif;
                        if ($order->payment_numbers->count()):
                            foreach ($order->payment_numbers as $payment_number):
                                $individual_listeners[$index]['orders_earnings']['real_earnings'] += $payment_number->price;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
            $listeners = array_merge($listeners, $individual_listeners);
        endif;
        if (count($listeners)):
            foreach ($listeners as $key => $row):
                @$created_at[$key] = $row['created_at'];
            endforeach;
            array_multisort($created_at, SORT_DESC, $listeners);
        endif;
        $page_data['listeners'] = $listeners;
        return View::make(Helper::acclayout('listeners'), $page_data);
    }

    public function ListenerProfile($listener_id) {

        $page_data = array(
            'page_title' => 'Профиль слушателя',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array()
        );
        if ($account = User::where('id', $listener_id)->first()):
            if ($account->group_id == 5):
                $page_data['profile'] = User_listener::where('id', $listener_id)->with('organization')->first();
                $page_data['profile']['group_id'] = 5;
            elseif ($account->group_id == 6):
                $page_data['profile'] = User_individual::where('id', $listener_id)->first();
                $page_data['profile']['group_id'] = 6;
            endif;
            return View::make(Helper::acclayout('listener-profile'), $page_data);
        else:
            App::abort(404);
        endif;
    }

    public function ListenerProfileEdit($listener_id) {

        $page_data = array(
            'page_title' => 'Профиль слушателя',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array(),
            'profile_group_id' => 0
        );

        if ($account = User::where('id', $listener_id)->first()):
            if ($account->group_id == 5):
                $page_data['profile'] = User_listener::where('id', $listener_id)->first();
                $page_data['profile_group_id'] = 5;
            elseif ($account->group_id == 6):
                $page_data['profile'] = User_individual::where('id', $listener_id)->first();
                $page_data['profile_group_id'] = 6;
            endif;
            return View::make(Helper::acclayout('listener-profile-edit'), $page_data);
        else:
            App::abort(404);
        endif;
    }

    public function ListenerProfileUpdate($listener_id) {

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);
        if (Request::ajax()):
            $account = User::where('id', $listener_id)->first();
            if ($account && $account->group_id == 5):
                $validator = Validator::make(Input::all(), Listener::$moderator_rules);
            elseif ($account && $account->group_id == 6):
                $validator = Validator::make(Input::all(), Individual::$moderator_rules);
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
            endif;
            if ($validator->passes()):
                if ($account->group_id == 5):
                    self::ListenerCompanyAccountUpdate($account, $listener_id, Input::all());
                    Event::fire(Route::currentRouteName(), array(array('title' => 'Слушатель: ' . User_listener::where('id', $listener_id)->pluck('fio'))));
                elseif ($account->group_id == 6):
                    self::ListenerIndividualAccountUpdate($account, $listener_id, Input::all());
                    Event::fire(Route::currentRouteName(), array(array('title' => 'Слушатель: ' . User_individual::where('id', $listener_id)->pluck('fio'))));
                endif;
                $json_request['status'] = TRUE;
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.success');
                $json_request['redirect'] = URL::route('moderator-listener-profile', $listener_id);
            else:
                $json_request['responseText'] = Lang::get('interface.UPDATE_PROFILE_LISTENER.fail');
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request, 200);
    }

    private function ListenerCompanyAccountUpdate($user, $listener_id, $post) {

        if ($listener = Listener::where('user_id', $user->id)->first()):
            $fio = explode(' ', $post['fio']);
            $user->name = (isset($fio[1])) ? $fio[1] : '';
            $user->surname = (isset($fio[0])) ? $fio[0] : '';
            $user->email = $post['email'];
            if ($post['active'] == 1 && ($user->active == 0 || $user->active == 2)):
                Event::fire('moderator-listener-profile-activated', array(array('title' => $post['fio'])));
            endif;
            $user->active = $post['active'];
            if ($post['active'] == 1):
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

    private function ListenerIndividualAccountUpdate($user, $listener_id, $post) {

        if ($individual = Individual::where('user_id', $user->id)->first()):
            $fio = explode(' ', $post['fio']);
            $user->name = (isset($fio[1])) ? $fio[1] : '';
            $user->surname = (isset($fio[0])) ? $fio[0] : '';
            $user->email = $post['email'];
            if ($post['active'] == 1 && ($user->active == 0 || $user->active == 2)):
                Event::fire('moderator-listener-profile-activated', array(array('title' => $post['fio'])));
            endif;
            $user->active = $post['active'];
            if ($post['active'] == 1):
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
            if ($individual->moderator_approve == 0 && $post['moderator_approve'] == 1):
                Event::fire('moderator-company-profile-approved', array(array('title' => $post['title'])));
                Event::fire('account.approved-profile', array(array('accountID' => $user->id)));
            endif;
            $individual->moderator_approve = $post['moderator_approve'];
            $individual->statistic = $post['statistic'];
            Orders::where('user_id', $user->id)->update(array('statistic' => $post['statistic']));
            $individual->save();
            $individual->touch();

            return TRUE;
        endif;
        return FALSE;
    }
    /****************************************************************************/
    /***************************** СТАТИСТИКА ***********************************/
    /****************************************************************************/
    public function statistic() {

        $period_begin = date("Y-m-d 00:00:00", (strtotime('first day of this month', time())));
        $period_end = date("Y-m-d 23:59:59");
        $period_index_end = date("Y-m-d 00:00:00");
        $month = array();
        $account_id = 0;
        $direction_id = 0;
        if (Session::has('account_id')):
            $account_id = Session::get('account_id');
        endif;
        if (Session::has('direction_id')):
            $direction_id = Session::get('direction_id');
        endif;
        if (Session::has('period_begin')):
            $period_begin = date('Y-m-d 00:00:00', strtotime(Session::get('period_begin')));
        endif;
        if (Session::has('period_begin')):
            $period_end = date('Y-m-d 23:59:59', strtotime(Session::get('period_end')));
            $period_index_end = date('Y-m-d 00:00:00', strtotime(Session::get('period_end')));
        endif;
        $ordersIDs = array();
        if ($direction_id):
            if ($coursesIDs = Directions::where('id', $direction_id)->first()->courses()->lists('id', 'code')):
                $OrderListeners = OrderListeners::whereIn('course_id', $coursesIDs)->with(array('order' => function ($query) use ($account_id) {
                    if ($account_id):
                        $query->where('user_id', $account_id);
                    endif;
                }))->get();
                if ($OrderListeners->count()):
                    foreach ($OrderListeners as $OrderListener):
                        if (!empty($OrderListener->order)):
                            $ordersIDs[$OrderListener->order_id] = $OrderListener->order_id;
                        endif;
                    endforeach;
                endif;
            endif;
        endif;

        $diffMonthsData = myDateTime::getDiffDate($period_end, $period_begin, NULL);
        if ($diffMonthsData['y'] > 0):
            $diffMonths = ($diffMonthsData['y'] * 12) + $diffMonthsData['m'];
        else:
            $diffMonths = $diffMonthsData['m'];
        endif;
        $format = 'd.m.y';
        if ($diffMonths >= 2):
            $format = 'm.y';
        endif;
        $index_start = (new myDateTime())->setDateString($period_begin)->format($format);
        $index_end = (new myDateTime())->setDateString($period_index_end)->format($format);
        /****************************************************************************/
        $all_orders_query = Orders::where('completed', 1)->where('statistic', TRUE)->where('created_at', '>=', $period_begin)->where('created_at', '<=', $period_end);
        if ($account_id):
            $all_orders_query = $all_orders_query->where('user_id', $account_id);
        endif;
        $all_orders_query = $all_orders_query->with('listeners', 'organization', 'individual');
        if ($direction_id && !empty($ordersIDs)):
            $all_orders_query = $all_orders_query->whereIn('id', $ordersIDs);
            $all_orders = $all_orders_query->get();
        endif;
        if ($direction_id && empty($ordersIDs)):
            $all_orders = array();
        endif;
        if (!$direction_id):
            $all_orders = $all_orders_query->get();
        endif;
        foreach ($all_orders as $order):
            if (!empty($order->organization) && $order->organization->statistic == 1):
                $tmp_orders[] = $order;
            elseif (!empty($order->individual) && $order->individual->statistic == 1):
                $tmp_orders[] = $order;
            endif;
        endforeach;
        $all_orders = $tmp_orders;
        $orders = array($index_start => 0);
        $orders_extended = array();
        $tmp_orders = array();
        $orders_extended_counts = array('orders' => 0, 'price' => 0);
        if (!empty($all_orders)):
            foreach ($all_orders as $order_index => $order):
                $tmp_orders[$order_index] = $order->toArray();
                $tmp_orders[$order_index]['created_at_origin'] = $tmp_orders[$order_index]['created_at'];
                $tmp_orders[$order_index]['created_at'] = $order->created_at->format($format);
            endforeach;
            if (!empty($tmp_orders)):
                $tmp_order_extended = array();
                $orders_extended_total = array();
                foreach ($tmp_orders as $order):
                    $orders[$order['created_at']] += 1;
                    $tmp_order_extended[$order['created_at']][] = $orders_extended_total[] = self::getStatisticOrdersExtended($order);
                endforeach;
                if (count($tmp_order_extended)):
                    foreach ($tmp_order_extended as $index => $order_extended):
                        $orders_extended_counts['orders'] += count($order_extended);
                        foreach ($order_extended as $orderExtended):
                            $orders_extended_counts['price'] += $orderExtended['price'];
                        endforeach;
                        $orders_extended[$index] = View::make(Helper::acclayout('assets.statistic.orders-table'), array('orders' => $order_extended,
                            'date' => $index))->render();
                    endforeach;
                    if (count($orders_extended)):
                        $orders_extended['total'] = View::make(Helper::acclayout('assets.statistic.orders-table'), array('orders' => $orders_extended_total,
                            'date' => 'период'))->render();
                    endif;
                endif;
            endif;
            if (!isset($orders[$index_end])):
                $orders[$index_end] = 0;
            endif;
        endif;
        /**********************************************************/
        $payments = array($index_start => 0);
        $payments_list = array();
        $payments_extended = array();
        $payments_extended_counts = array('payments' => 0, 'price' => 0);
        $all_payments = array();
        foreach (OrderPayments::where('payment_date', '>=', $period_begin)->where('payment_date', '<=', $period_end)->orderBy('payment_date', 'ASC')->with('order.listeners', 'order.organization', 'order.individual')->get() as $payment):
            if ($payment->order->statistic):
                if($account_id):
                    if($payment->order->user_id == $account_id):
                        $all_payments[$payment->id]['price'] = $payment->price;
                        $all_payments[$payment->id]['payment_number'] = $payment->payment_number;
                        $all_payments[$payment->id]['payment_date_origin'] = $payment->payment_date;
                        $all_payments[$payment->id]['payment_date'] = Carbon::createFromTimestamp(strtotime($payment->payment_date))->format($format);
                        $all_payments[$payment->id]['order'] = self::getStatisticPaymentsExtended($payment->order);
                    endif;
                else:
                    $all_payments[$payment->id]['price'] = $payment->price;
                    $all_payments[$payment->id]['payment_number'] = $payment->payment_number;
                    $all_payments[$payment->id]['payment_date_origin'] = $payment->payment_date;
                    $all_payments[$payment->id]['payment_date'] = Carbon::createFromTimestamp(strtotime($payment->payment_date))->format($format);
                    $all_payments[$payment->id]['order'] = self::getStatisticPaymentsExtended($payment->order);
                endif;
            endif;
        endforeach;
        $payments_extended_total = $all_payments;
        if (!empty($all_payments)):
            foreach ($all_payments as $payment_id => $payment):
                $payments_list[$payment['payment_date']] = array();
            endforeach;
            foreach ($payments_list as $index => $payment):
                foreach ($all_payments as $payment_id => $all_payment):
                    if ($all_payment['payment_date'] == $index):
                        $payments_list[$index][] = $all_payment;
                    endif;
                endforeach;
            endforeach;
            foreach ($payments_list as $index => $payment):
                $price = 0;
                foreach ($payment as $payment_single):
                    $price += $payment_single['price'];
                endforeach;
                $payments[$index] = $price;
                $payments_extended_counts['payments'] += count($payment);
                $payments_extended_counts['price'] += $price;
            endforeach;
            if (!empty($payments_list)):
                foreach ($payments_list as $index => $payment_extended):
                    $payments_extended[$index] = View::make(Helper::acclayout('assets.statistic.payments-table'), array('payments' => $payment_extended,
                        'date' => $index))->render();
                endforeach;
                if (count($payments_extended)):
                    $payments_extended['total'] = View::make(Helper::acclayout('assets.statistic.payments-table'), array('payments' => $payments_extended_total,
                        'date' => 'период'))->render();
                endif;
            endif;
        endif;
        if (!isset($payments[$index_end])):
            $payments[$index_end] = 0;
        endif;
        /**********************************************************/
        $page_data = array(
            'page_title' => 'Статистика',
            'page_description' => '',
            'page_keywords' => '',
            'account_selected' => $account_id,
            'direction_selected' => $direction_id,
            'period_begin' => date('d.m.Y', strtotime($period_begin)),
            'period_end' => date('d.m.Y', strtotime($period_end)),
            'orders_chart' => $orders,
            'payments_chart' => $payments,
            'payments_list' => $payments_list,
            'diffMonths' => $diffMonths,
            'orders_extended_counts' => $orders_extended_counts,
            'orders_extended' => $orders_extended,
            'payments_extended' => $payments_extended,
            'payments_extended_counts' => $payments_extended_counts
        );
        return View::make(Helper::acclayout('statistic'), $page_data);
    }

    public function statisticSetPeriod() {

        return Redirect::route('moderator-statistic')
            ->with('period_begin', Input::get('period_begin'))
            ->with('period_end', Input::get('period_end'))
            ->with('account_id', Input::get('account_id'))
            ->with('direction_id', Input::get('direction_id'));
    }

    private function getStatisticOrdersExtended($order) {

        $result_orders['number'] = getOrderNumber($order);
        $result_orders['link'] = URL::route('moderator-order-extended', $order['id']);
        $result_orders['listeners'] = 0;
        $result_orders['price'] = 0;
        $result_orders['created'] = (new myDateTime())->setDateString($order['created_at_origin'])->format('d.m.Y H:i');
        if (count($order['listeners'])):
            foreach ($order['listeners'] as $listener):
                $result_orders['listeners'] += 1;
                $result_orders['price'] += $listener['price'];
            endforeach;
        endif;
        if (!empty($order['organization'])):
            $result_orders['purchaser'] = array(
                'id' => $order['organization']['id'],
                'group_id' => 4,
                'name' => $order['organization']['title'],
                'link' => URL::route('moderator-company-profile', $order['organization']['id'])
            );
        elseif (!empty($order['individual'])):
            $result_orders['purchaser'] = array(
                'id' => $order['individual']['id'],
                'group_id' => 6,
                'name' => $order['individual']['fio'],
                'link' => URL::route('moderator-listener-profile', $order['individual']['id'])
            );
        endif;
        return $result_orders;
    }

    private function getStatisticPaymentsExtended($order) {

        $result_orders['number'] = getOrderNumber($order);
        $result_orders['link'] = URL::route('moderator-order-extended', $order['id']);
        $result_orders['listeners'] = 0;
        $result_orders['price'] = 0;
        $result_orders['created'] = (new myDateTime())->setDateString($order['created_at_origin'])->format('d.m.Y H:i');
        if (count($order['listeners'])):
            foreach ($order['listeners'] as $listener):
                $result_orders['listeners'] += 1;
                $result_orders['price'] += $listener['price'];
            endforeach;
        endif;
        if (!empty($order['organization'])):
            $result_orders['purchaser'] = array(
                'id' => $order['organization']['id'],
                'group_id' => 4,
                'name' => $order['organization']['title'],
                'link' => URL::route('moderator-company-profile', $order['organization']['id'])
            );
        elseif (!empty($order['individual'])):
            $result_orders['purchaser'] = array(
                'id' => $order['individual']['id'],
                'group_id' => 6,
                'name' => $order['individual']['fio'],
                'link' => URL::route('moderator-listener-profile', $order['individual']['id'])
            );
        endif;
        if (count($order['payment_numbers'])):
            foreach ($order['payment_numbers'] as $payment_number):
                $result_orders['payment_numbers'][] = $payment_number;
            endforeach;
        endif;
        return $result_orders;
    }
}