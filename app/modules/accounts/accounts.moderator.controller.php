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
                Route::post('order/{order_id}/payment-number/store', array('as' => 'payment-order-number-store', 'uses' => $class . '@OrderPaymentNumberStore'));


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

        print_r(Input::all());
        exit;

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),OrderPayments::$rules);
            if($validator->passes()):
                if (OrderPayments::create(array('order_id'=>$order_id,'price'=>Input::get('price'),'payment_number'=>Input::get('payment_number'),'payment_date'=>Input::get('payment_date')))):
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