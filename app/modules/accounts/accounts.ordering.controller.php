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
//            $lastOrderNumber = (new Orders)->getLastOrderNumber(true);
            $lastFreeOrderNumber = (new Orders)->getLastFreeOrderNumber();
            if($order = Orders::create(array('user_id'=>Auth::user()->id,'number'=>$lastFreeOrderNumber,'completed'=>Input::get('completed')))):
                $accountDiscount = getAccountDiscount();
                $coursesCountDiscount = coursesCountDiscount(Input::get('courses'));
                foreach(Courses::whereIn('id',Input::get('courses'))->with('direction')->get() as $course):
                    $discountPrice = calculateDiscount(array($course->direction->discount,$course->discount,$accountDiscount,$coursesCountDiscount),$course->price);
                    $course_price = $course->price;
                    if ($discountPrice !== FALSE):
                        $course_price = $discountPrice;
                    endif;
                    foreach(Input::get('listeners') as $course_id => $listeners):
                        if ($course->id == $course_id):
                            foreach($listeners as $listener_id):
                                OrderListeners::create(array('order_id'=>$order->id,'course_id'=>$course_id,'user_id'=>$listener_id,'price'=>$course_price));
                            endforeach;
                        endif;
                    endforeach;
                endforeach;
                setcookie("ordering", "", time() - 3600,'/');
                $approve = 0; $is_organization = FALSE;
                if (isOrganization()):
                    $approve = User_organization::where('id',Auth::user()->id)->pluck('moderator_approve');
                    $is_organization = TRUE;
                elseif(isIndividual()):
                    $approve = User_individual::where('id',Auth::user()->id)->pluck('moderator_approve');
                endif;
                if (!$approve):
                    if ($is_organization):
                        Event::fire('organization.order-puy-no-approve',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('organization-order',$order->id))));
                    else:
                        Event::fire('organization.order-puy-no-approve',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('individual-order',$order->id))));
                    endif;
                else:
                    if ($is_organization):
                        Event::fire('organization.order-puy',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('organization-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')))));
                    else:
                        Event::fire('organization.order-puy',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('individual-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')))));
                    endif;
                endif;
                Event::fire('moderator.order.new',array(array('accountID'=>0,'order'=>getOrderNumber($order))));
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
            endif;
            return TRUE;
        endif;
        return FALSE;
    }
}