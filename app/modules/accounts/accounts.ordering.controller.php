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
            Route::group(array('before' => 'auth.status', 'prefix' => Auth::user()->group()->pluck('dashboard')), function() use ($class) {
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
            $accountGroup = User::where('id',Auth::user()->id)->pluck('group_id');
            if ($accountGroup == 6):
                $courses = array();
                foreach (getJsonCookieData('ordering') as $course):
                    $courses[$course] = array(Auth::user()->id);
                endforeach;
                setcookie('ordering',json_encode($courses),0,'/');
            endif;
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
            $lastOrderNumber = (new Orders)->getLastOrderNumber(true);
//            $lastFreeOrderNumber = (new Orders)->getLastFreeOrderNumber();
            if($order = Orders::create(array('user_id'=>Auth::user()->id,'number'=>$lastOrderNumber,'completed'=>Input::get('completed')))):
                $accountDiscount = getAccountDiscount();
                $globalDiscount = getGlobalDiscount();
                $coursesCountDiscount = coursesCountDiscount(Input::get('listeners'));
                foreach(Courses::whereIn('id',Input::get('courses'))->with('direction')->get() as $course):
                    $discountPrice = FALSE;
                    if($course->direction->use_discount && $course->use_discount):
                        $discountPrice = calculateDiscount(array($course->direction->discount,$course->discount,$accountDiscount,$coursesCountDiscount,$globalDiscount),$course->price);
                    endif;
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
                $approve = 0;
                if (isOrganization()):
                    $approve = User_organization::where('id',Auth::user()->id)->pluck('moderator_approve');
                    $name = User_organization::where('id',Auth::user()->id)->pluck('title');
                elseif(isIndividual()):
                    $approve = User_individual::where('id',Auth::user()->id)->pluck('moderator_approve');
                    $name = User_individual::where('id',Auth::user()->id)->pluck('fio');
                endif;
                if (!$approve):
                    if (isOrganization()):
                        Event::fire('organization.order-puy-no-approve',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('organization-order',$order->id))));
                    elseif(isIndividual()):
                        Event::fire('individual.order-puy-no-approve',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('individual-order',$order->id))));
                    endif;
                else:
                    if (isOrganization()):
                        Event::fire('organization.order-puy',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'order_link'=>URL::route('organization-order',$order->id),'document_link'=>URL::route('organization-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')))));
                    elseif(isIndividual()):
                        Event::fire('individual.order-puy',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'order_link'=>URL::route('individual-order',$order->id),'document_link'=>URL::route('individual-listener-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')))));
                    endif;
                endif;
                Event::fire('moderator.order.new',array(array('accountID'=>0,'link'=>URL::to('moderator/order/'.$order->id.'/extended'),'order'=>getOrderNumber($order),'organization'=>@$name)));
                return Redirect::to(AuthAccount::getStartPage().'/#orgNotifications');
            endif;
        else:
            return Redirect::route('ordering-select-courses')->with('message','Не выбраны курсы для покупки');
        endif;
    }

    public static function closeOrder($order_id,$listener_id = NULL){

        if (is_null($listener_id)):
            $listener_id = Auth::user()->id;
        endif;
        if($order = Orders::where('id',$order_id)->where('completed',1)->where('close_status',0)->with('listeners')->first()):
            $close_allowed = TRUE;
            foreach($order->listeners as $listener):
                if ($listener->over_status == 0):
                    $close_allowed = FALSE;
                    break;
                endif;
            endforeach;
            if ($close_allowed):
                foreach($order->listeners as $listener):
                    $certificate_number = (new OrderListeners)->getLastCertificateNumber(true);
                    OrderListeners::where('id',$listener->id)->where('certificate_number',0)->update(array('certificate_number'=>$certificate_number,'certificate_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                endforeach;
                $lastOrderCompletionNumber = (new Orders)->getLastOrderCompletionNumber(true);
                Orders::where('id',$order->id)->update(array('number_completion'=>$lastOrderCompletionNumber,'close_status'=>1,'close_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
                (new AccountsDocumentsController)->generateAllDocuments($order->id);
                if(isCompanyListener()):
                    Event::fire('organization.order.closed-join',array(array('accountID'=>User_listener::where('id',Auth::user()->id)->first()->organization()->pluck('id'),'order'=>getOrderNumber($order),'link'=>URL::to('company/order/'.$order_id))));
                    #Event::fire('organization.order.closed',array(array('accountID'=>User_listener::where('id',Auth::user()->id)->first()->organization()->pluck('id'),'order'=>getOrderNumber($order),'link'=>URL::to('organization/order/'.$order_id))));
                    #Event::fire('organization.order.closed-documents',array(array('accountID'=>User_listener::where('id',Auth::user()->id)->first()->organization()->pluck('id'),'order'=>getOrderNumber($order),'link'=>URL::to('organization/order/'.$order_id))));
                elseif(isIndividual()):
                    Event::fire('individual.order.closed-join',array(array('accountID'=>Auth::user()->id,'order'=>getOrderNumber($order),'link'=>URL::route('individual-order',$order_id))));
                endif;
                Event::fire('moderator.order.closed',array(array('accountID'=>0,'link'=>URL::to('moderator/order/'.$order->id.'/extended'),'order'=>getOrderNumber($order))));
            endif;
            return TRUE;
        endif;
        return FALSE;
    }
}