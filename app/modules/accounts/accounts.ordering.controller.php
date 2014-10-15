<?php

class AccountsOrderingController extends BaseController {

    public static $name = 'ordering';
    public static $group = 'accounts';
    public static $entity = 'ordering';
    public static $entity_name = 'Оформление заказа';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        if (isOrganizationORIndividual()):
            Route::group(array('before' => 'auth.status', 'prefix' => Auth::user()->group()->pluck('name')), function() use ($class) {
                Route::get('ordering/select-courses', array('as' => 'ordering-select-courses', 'uses' => $class . '@OrderingSelectCourses'));
                Route::get('ordering/select-listeners', array('as' => 'ordering-select-listeners', 'uses' => $class . '@OrderingSelectListeners'));
                Route::post('ordering/courses-store', array('before' => 'csrf', 'as' => 'ordering-courses-store', 'uses' => $class . '@OrderingCoursesStore'));
                Route::post('ordering/listeners-store', array('before' => 'csrf', 'as' => 'ordering-listeners-store', 'uses' => $class . '@OrderingListenersStore'));
            });
        endif;

        Event::listen('order.created', function ($data) { });
        Event::listen('order.archived', function ($data) { });
        Event::listen('order.deleted', function ($data) { });
        Event::listen('order.changed', function ($data) { });
        Event::listen('order.payment', function ($data) { });
        Event::listen('order.closed', function ($data) { });
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

    public function OrderingSelectCourses(){

        $page_data = array(
            'page_title'=> Lang::get('seo.ORDERING.select_courses.title'),
            'page_description'=> Lang::get('seo.ORDERING.select_courses.description'),
            'page_keywords'=> Lang::get('seo.ORDERING.select_courses.keywords'),
        );
        return View::make(Helper::acclayout('ordering.courses-selected'),$page_data);
    }

    public function OrderingSelectListeners(){

        if (!hasCookieData('ordering')):
            return Redirect::route('ordering-select-courses')->with('message','Не выбраны курсы для покупки');
        else:
            $page_data = array(
                'page_title'=> Lang::get('seo.ORDERING.select_listeners.title'),
                'page_description'=> Lang::get('seo.ORDERING.select_listeners.description'),
                'page_keywords'=> Lang::get('seo.ORDERING.select_listeners.keywords'),
            );
            return View::make(Helper::acclayout('ordering.listeners-selected'),$page_data);
        endif;
    }

    public function OrderingCoursesStore(){

        $validator = Validator::make(Input::all(),array('courses'=>'required'));
        if($validator->passes() && hasCookieData('ordering')):
            return Redirect::route('ordering-select-listeners');
        else:
            return Redirect::route('ordering-select-courses')->with('message','Не выбраны курсы для покупки');
        endif;
    }

    public function OrderingListenersStore(){

        if (!hasCookieData('ordering')):
            return Redirect::route('ordering-select-listeners')->with('message','Не выбраны сотрудники');
        endif;

        $validator = Validator::make(Input::all(),array('courses'=>'required','listeners'=>'required','completed'=>'required'));
        if($validator->passes()):
            $listeners = Input::get('listeners');
            foreach(Input::get('courses') as $course_id):
                if (!isset($listeners[$course_id]) || empty($listeners[$course_id])):
                    return Redirect::route('ordering-select-listeners')->with('message','Сотрудники выбраны не для всех курсов в списке');
                endif;
            endforeach;
            $lastOrderNumber = Orders::where('completed',1)->orderBy('number','DESC')->pluck('number');
            if($order = Orders::create(array('user_id'=>Auth::user()->id,'number'=>$lastOrderNumber+1,'completed'=>Input::get('completed')))):
                foreach(Courses::whereIn('id',Input::get('courses'))->get() as $course):
                    foreach(Input::get('listeners') as $course_id => $listeners):
                        if ($course->id == $course_id):
                            foreach($listeners as $listener_id):
                                OrderListeners::create(array('order_id'=>$order->id,'course_id'=>$course_id,'user_id'=>$listener_id,'price'=>$course->price));
                            endforeach;
                        endif;
                    endforeach;
                endforeach;
                setcookie("ordering", "", time() - 3600);
                Event::fire('order.created',array('order'=>$order));
                return Redirect::to(AuthAccount::getStartPage());
            endif;
        else:
            return Redirect::route('ordering-select-courses')->with('message','Не выбраны курсы для покупки');
        endif;
    }

    public static function closeOrder($order_id,$listener_id = NULL){

        if (is_null($listener_id)):
            $listener_id = Auth::user()->id;
        endif;
        if($order = Orders::where('id',$order_id)->where('completed',1)->where('archived',0)->where('close_status',0)->with('listeners')->first()):
            $close_allowed = TRUE;
            foreach($order->listeners as $listener):
                if ($listener->access_status == 0 || $listener->start_status == 0 || $listener->over_status == 0):
                    $close_allowed = FALSE;
                    break;
                endif;
            endforeach;
            if ($close_allowed):
                Orders::where('id',$order->id)->update(array('close_status'=>1,'close_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                Event::fire('order.closed',array('order'=>$order));
            endif;
            return TRUE;
        endif;
        return FALSE;
    }
}