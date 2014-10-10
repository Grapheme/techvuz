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

                Route::get('orders', array('as' => 'moderator-orders-list', 'uses' => $class . '@OrdersList'));
                Route::get('order/{order_id}/extended', array('as' => 'moderator-order-extended', 'uses' => $class . '@OrderExtendedView'));
                Route::post('order/{order_id}/extended/set-access/{order_listener_id}', array('as' => 'order-listener-access', 'uses' => $class . '@changeOrderListenerAccess'));
                Route::post('order/{order_id}/extended/set-status', array('as' => 'change-order-status', 'uses' => $class . '@changeOrderStatus'));

                Route::post('order/{order_id}/payment-number/store', array('before' => 'csrf', 'as' => 'payment-order-number-store', 'uses' => $class . '@OrderPaymentNumberStore'));
                Route::patch('order/{order_id}/payment-number/update', array('before' => 'csrf', 'as' => 'payment-order-number-update', 'uses' => $class . '@OrderPaymentNumberUpdate'));
                Route::delete('order/{order_id}/payment-number/delete/{payment_order_id}', array('before' => 'csrf', 'as' => 'payment-order-number-delete', 'uses' => $class . '@OrderPaymentNumberDelete'));

                Route::get('listeners', array('as' => 'moderator-listeners-list', 'uses' => $class . '@ListenersList'));
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
        );
        return View::make(Helper::acclayout('companies'),$page_data);
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
                if ($order->payment_status == 1 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>5,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
                    $json_request['responseOrderStatus'] = 5;
                elseif ($order->payment_status == 6 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>2,'payment_date'=>date('c'),'updated_at'=>date('c')));
                    $json_request['responseOrderStatus'] = 2;
                elseif ($order->payment_status == 3 && $countAccess == $total_count):
                    Orders::where('id',$order_id)->update(array('payment_status'=>4,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
                    $json_request['responseOrderStatus'] = 4;
                elseif($order->payment_status == 2 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>6,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
                    $json_request['responseOrderStatus'] = 6;
                elseif($order->payment_status == 4 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>3,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
                    $json_request['responseOrderStatus'] = 3;
                elseif($order->payment_status == 5 && $countAccess == 0):
                    Orders::where('id',$order_id)->update(array('payment_status'=>1,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
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
                $order->save();
                $order->touch();
                switch(Input::get('status')):
                    case 2: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>date('c')));
                            break;
                    case 4: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>date('c')));
                            break;
                    case 5: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>date('c')));
                            break;
                    case 6: Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>0,'updated_at'=>date('c')));
                            break;
                endswitch;
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
            if ($payment_summa >= $total_summa):
                Orders::where('id',$order_id)->update(array('payment_status'=>2,'payment_date'=>date('c'),'updated_at'=>date('c')));
                Orders::where('id',$order_id)->first()->listeners()->update(array('access_status'=>1,'updated_at'=>date('c')));
            elseif($payment_summa > 0 && $payment_summa < $total_summa):
                Orders::where('id',$order_id)->update(array('payment_status'=>3,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
            else:
                Orders::where('id',$order_id)->update(array('payment_status'=>1,'payment_date'=>'0000-00-00 00:00:00','updated_at'=>date('c')));
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
        );
        return View::make(Helper::acclayout('listeners'),$page_data);
    }
}