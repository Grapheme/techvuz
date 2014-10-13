<?php

class AccountsListenerController extends BaseController {

    public static $name = 'listener';
    public static $group = 'accounts';
    public static $entity = 'listener';
    public static $entity_name = 'Действия сотрудника организации';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (isCompanyListener()):
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function() use ($class) {
                Route::get('profile', array('as' => 'listener-profile', 'uses' => $class . '@ListenerProfile'));
                Route::get('profile/edit', array('as' => 'listener-profile-edit', 'uses' => $class . '@ListenerProfileEdit'));
                Route::patch('profile/update', array('before' => 'csrf', 'as' => 'listener-profile-update', 'uses' => $class . '@ListenerProfileUpdate'));

                Route::get('study', array('as' => 'listener-study', 'uses' => $class . '@ListenerStudyList'));
                Route::get('study/course/{course_translite_title}', array('as' => 'listener-study-course', 'uses' => $class . '@ListenerStudyCourse'));
                Route::post('study/course/{study_course_id}/lecture/{lecture_id}/download', array('before' => 'csrf', 'as' => 'listener-study-download-lecture', 'uses' => $class . '@ListenerStudyLectureDownload'));

                Route::get('notifications', array('as' => 'listener-notifications', 'uses' => $class . '@ListenerNotificationsList'));
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

    public function ListenerProfile(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.keywords'),
            'profile' => User_listener::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function ListenerProfileEdit(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_PROFILE.keywords'),
            'profile' => User_listener::where('id',Auth::user()->id)->first()
        );
        return View::make(Helper::acclayout('profile-edit'),$page_data);
    }

    public function ListenerProfileUpdate(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax() && isCompanyListener()):
            $validator = Validator::make(Input::all(),Listener::$update_rules);
            if($validator->passes()):
                if (self::ListenerAccountUpdate(Input::all())):
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.success_save');
                    $json_request['redirect'] = URL::route('listener-profile');
                    $json_request['status'] = TRUE;
                else:
                    $json_request['responseText'] = Lang::get('interface.DEFAULT.fail');
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

    private function ListenerAccountUpdate($post){

        $user = Auth::user();
        if($listener = Listener::where('user_id',$user->id)->first()):
            $fio = explode(' ',$post['fio']);
            $user->name = (isset($fio[1]))?$fio[1]:'';
            $user->surname = (isset($fio[0]))?$fio[0]:'';
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
        else:
            return FALSE;
        endif;
    }

    public function ListenerStudyList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_STUDY_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_STUDY_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_STUDY_LIST.keywords'),
        );
        return View::make(Helper::acclayout('study-list'),$page_data);
    }

    public function ListenerStudyCourse($course_translit){

        $listenerCourse = OrderListeners::where('id',(int) $course_translit)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->first();
        if (!$listenerCourse):
            return Redirect::route('listener-study');
        endif;
        $module = Courses::where('id',$listenerCourse->course_id)->with(array('chapters'=>function($query){
            $query->orderBy('order');
            $query->with(array('lectures'=>function($query_lecture){
                $query_lecture->orderBy('order');
            }));
            $query->with('test');
        }))->with('test')->first();

//        Helper::dd($module);

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.keywords'),
            'study_course' => $listenerCourse,
            'module' => $module
        );
        return View::make(Helper::acclayout('study-course'),$page_data);
    }

    public function ListenerStudyLectureDownload($study_course_id,$lecture_id){

        $listenerCourse = OrderListeners::where('id',$study_course_id)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->first();
        if($listenerCourse && Lectures::where('id',$lecture_id)->exists()):
            $lecture = Lectures::where('id',$lecture_id)->with('document')->first()->toArray();
            if (isset($lecture['document']['path']) && File::exists(public_path($lecture['document']['path']))):
                $headers = returnDownloadHeaders($lecture['document']);
                return Response::download(public_path($lecture['document']['path']), $lecture['document']['original_name'], $headers);
            else:
                return Redirect::back();
            endif;
        else:
            return Redirect::back();
        endif;
    }

    public function CompanyNotificationsList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_NOTIFICATION_LIST.keywords'),
        );
        return View::make(Helper::acclayout('notifications'),$page_data);
    }

    /**************************************************************************/

}