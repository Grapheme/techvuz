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
                Route::get('study/course/{course_translite_title}/test/{test_id}/confirm', array('as' => 'listener-start-study-testing', 'uses' => $class . '@ListenerStartStudyTesting'));
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
        elseif($notification_id == 'selected' && Input::has('messages')):
            $notificationIDs = Input::get('messages');
            $messages = Dictionary::valuesBySlug('system-messages',function($query) use ($notificationIDs) {
                $query->whereIn('dictionary_values.id',$notificationIDs);
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
    /******************************* STUDY ************************************/
    /**************************************************************************/

    public function ListenerStudyList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_STUDY_PROGRESS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('study-list'),$page_data);
    }

    public function ListenerStudyCourse($course_translit){

        $listenerCourseID = (int) $course_translit;
        $listenerCourse = OrderListeners::where('id',$listenerCourseID)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->with('order')
            ->first();
        if (empty($listenerCourse) || !$listenerCourseID || $listenerCourse->order->close_status == 1):
            return Redirect::route('individual-study');
        endif;
        $module = Courses::where('id',$listenerCourse->course_id)->with(array('chapters'=>function($query) use ($listenerCourseID){
            $query->orderBy('order');
            $query->with(array('lectures'=>function($query_lecture){
                $query_lecture->orderBy('order');
                $query_lecture->with('downloaded_lecture');
            }));
            $query->with('test');
            $query->with(array('test.user_test_has100'=>function($query) use ($listenerCourseID){
                $query->where('order_listeners_id',$listenerCourseID);
            }));
            $query->with(array('test.user_test_success'=>function($query) use ($listenerCourseID){
                $query->where('order_listeners_id',$listenerCourseID);
            }));
        }))->with('test')->with(array('metodicals'=>function($query){
            $query->orderBy('order');
            $query->with('document');
        }))->first();
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
            ->with('order')
            ->first();
        if (empty($listenerCourse)  || $listenerCourse->order->close_status == 1):
            return Redirect::route('individual-study');
        endif;
        if(Lectures::where('id',$lecture_id)->exists()):
            $lecture = Lectures::where('id',$lecture_id)->with('document')->first()->toArray();
            if (isset($lecture['document']['path']) && File::exists(public_path($lecture['document']['path']))):
                if($listenerCourse->start_status == 0):
                    #Event::fire('individual.study.begin',array(array('accountID'=>Auth::user()->id,'course'=>OrderListeners::where('id',$study_course_id)->first()->course()->pluck('code'),'listener'=>User_individual::where('id',Auth::user()->id)->pluck('fio'))));
                endif;
                Event::fire('listener.start.course.study', array(array('listener_course_id'=>$study_course_id)));
                if(!User_lectures_download::where('user_id',Auth::user()->id)->where('lecture_id',$lecture_id)->exists()):
                    User_lectures_download::create(array('user_id'=>Auth::user()->id,'lecture_id'=>$lecture_id));
                endif;
                $headers = returnDownloadHeaders($lecture['document']);
                return Response::download(public_path($lecture['document']['path']), $lecture['document']['original_name'], $headers);
            else:
                return Redirect::back();
            endif;
        else:
            return Redirect::back();
        endif;
    }

    public function ListenerStudyLecturesDownload($study_course_id){

        $listenerCourse = OrderListeners::where('id',$study_course_id)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->with('order')
            ->first();
        if (empty($listenerCourse)  || $listenerCourse->order->close_status == 1):
            return Redirect::route('individual-study');
        endif;
        if(Lectures::where('course_id',$listenerCourse->course_id)->exists()):
            $lectures = Lectures::where('course_id',$listenerCourse->course_id)->with('document')->get();
            $documents = array();
            foreach($lectures as $index => $lecture):
                if(!User_lectures_download::where('user_id',Auth::user()->id)->where('lecture_id',$lecture->id)->exists()):
                    User_lectures_download::create(array('user_id'=>Auth::user()->id,'lecture_id'=>$lecture->id));
                endif;
                $lecture = $lecture->toArray();
                if (isset($lecture['document']['path']) && File::exists(public_path($lecture['document']['path']))):
                    $documents[$index]['original_name'] = BaseController::stringTranslite($lecture['document']['original_name'],NULL,'/[^a-z0-9-\.]/');
                    $documents[$index]['module_title'] = $lecture['title'];
                    $documents[$index]['path'] = preg_replace('|([/]+)|s', '/', public_path($lecture['document']['path']));
                endif;
            endforeach;
            if (!empty($documents)):
                $readme = "Список файлов:\n";
                foreach($documents as $index => $document):
                    File::copy($document['path'],storage_path($document['original_name']));
                    $modules_documents[$index] = storage_path($document['original_name']);
                    $readme .= ($index+1).") ".$document['original_name']." - ".$documents[$index]['module_title']."\n";
                endforeach;
                $zipFilePath = storage_path(sha1(time().Auth::user()->id).'.zip');
                $zipper = new \Chumper\Zipper\Zipper();
                $zipper->make($zipFilePath)->add($modules_documents)->addString('README.txt',$readme)->close();
                if (File::exists($zipFilePath)):
                    foreach($modules_documents as $index => $document):
                        if (File::exists($document)):
                            File::delete($document);
                        endif;
                    endforeach;
                    Event::fire('listener.start.course.study', array(array('listener_course_id'=>$study_course_id)));
                    $headers = returnZipDownloadHeaders($zipFilePath);
                    return Response::download($zipFilePath, 'all-lectures.zip.', $headers);
                endif;
            endif;
        endif;
        return Redirect::back();
    }

    /**************************************************************************/
    /**************************** TESTING *************************************/
    /**************************************************************************/

    public function ListenerStudyTesting($course_translit,$test_id){

        $listenerCourse = OrderListeners::where('id',(int) $course_translit)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->with('order')
            ->first();
        if (empty($listenerCourse)  || $listenerCourse->order->close_status == 1):
            return Redirect::route('listener-study');
        endif;
        $test = CoursesTests::where('id',$test_id)
            ->where('course_id',$listenerCourse->course_id)
            ->where('active',1)
            ->with('course')
            ->with('chapter')
            ->with('questions.answers')
            ->first();
        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_STUDY_COURSE.keywords'),
            'study_course' => $listenerCourse,
            'test' => $test
        );
        return View::make(Helper::acclayout('study-test'),$page_data);
    }

    public function ListenerStartStudyTesting($course_translite_title,$test_id){

        $listenerCourse = OrderListeners::where('id',(int) $course_translite_title)
            ->where('user_id',Auth::user()->id)
            ->where('access_status',1)
            ->where('start_final_test',0)
            ->where('over_status',0)
            ->first();
        if ($listenerCourse):
            $listenerCourse->start_final_test = 1;
            $listenerCourse->start_final_test_date = date('Y-m-d H:i:s');
            $listenerCourse->save();
            $listenerCourse->touch();
            return Redirect::back();
        else:
            return Redirect::route('listener-study');
        endif;

    }

    public function ListenerStudyTestFinish($study_course_id,$test_id){

        $listenerCourse = OrderListeners::where('id',$study_course_id)->where('user_id',Auth::user()->id)->where('access_status',1)->with('order')->with('course')->first();
        $test = CoursesTests::where('id',$test_id)->where('course_id',$listenerCourse->course_id)->where('active',1)->with('questions.answers')->first();
        if (!$listenerCourse || !$test || $listenerCourse->order->close_status == 1):
            return Redirect::route('individual-study');
        endif;
        $validator = Validator::make(Input::all(),array('questions'=>'required','time_attempt'=>'required'));
        if($validator->passes()):
            $questions_answers = array();
            $user_questions_answers = Input::get('questions');
            foreach($test->questions as $question):
                foreach($question->answers as $answer):
                    if ($answer->correct == 1):
                        $questions_answers[$question->id] = $answer->id;
                    endif;
                endforeach;
            endforeach;
            $test_max_balls = count($questions_answers);
            $test_user_balls = 0;
            foreach($questions_answers as $question_id => $answer_id):
                if (isset($user_questions_answers[$question_id]) && $user_questions_answers[$question_id] == $answer_id):
                    $test_user_balls++;
                endif;
            endforeach;
            $success_test_percent = Config::get('site.success_test_percent') ? Config::get('site.success_test_percent') : 70;
            $insert = array(
                'order_listeners_id' => $study_course_id,
                'chapter_id' => $test->chapter_id,
                'test_id' => $test->id,
                'data_results' => json_encode($user_questions_answers),
                'result_attempt' => @round($test_user_balls/$test_max_balls,3)*100,
                'time_attempt' => Input::get('time_attempt')
            );
            $listenerTest = OrdersListenersTests::create($insert);
            Event::fire('listener.start.course.study', array(array('listener_course_id'=>$study_course_id)));
            $course_translite_title = $listenerCourse->id.'-'.BaseController::stringTranslite($listenerCourse->course->title,100);
            if ($listenerTest->result_attempt >= $success_test_percent):
                if ($test->chapter_id == 0):
                    Event::fire('listener.over.course.study', array(array('listener_course_id'=>$study_course_id)));
                    Event::fire('listener.study-finish',array(array('accountID'=>Auth::user()->id,'course'=>OrderListeners::where('id',$study_course_id)->first()->course()->pluck('code'))));
                    AccountsOrderingController::closeOrder($listenerCourse->order_id);
                    return Redirect::route('listener-study-test-result',array('course_translite_title'=>$course_translite_title,'study_test_id'=>$listenerTest->id))->with('message.text',Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.success_course_test').' '.$listenerTest->result_attempt .'%</h4>')->with('message.status','test-result');
                else:
                    return Redirect::route('listener-study-test-result',array('course_translite_title'=>$course_translite_title,'study_test_id'=>$listenerTest->id))->with('message.text',Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.success_chapter_test').' '.$listenerTest->result_attempt .'%</h4>')->with('message.status','test-result');
                endif;
            else:
                return Redirect::route('listener-study-test-result',array('course_translite_title'=>$course_translite_title,'study_test_id'=>$listenerTest->id))->with('message.text',Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.fail').' '.$listenerTest->result_attempt .'%</h4>')->with('message.status','test-result')->with('message.show_repeat',TRUE);
            endif;
        else:
            return Redirect::back()->with('message',Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.empty_answers'));
        endif;
    }

    public function ListenerStudyTestResult($course_translit,$study_test_id){

        if (!Session::has('message.status') || Session::get('message.status') != 'test-result'):
            return Redirect::route('individual-study');
        endif;

        $listenerCourse = OrderListeners::where('id',(int) $course_translit)->where('user_id',Auth::user()->id)->where('access_status',1)->with('order')->first();
        $listenerTest = OrdersListenersTests::where('id',$study_test_id)->where('order_listeners_id',$listenerCourse->id)->with('test.course')->first();
        if (!$listenerCourse || !$listenerTest):
            return Redirect::route('listener-study');
        endif;
        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENER_STUDY_TEST_RESULT.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENER_STUDY_TEST_RESULT.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENER_STUDY_TEST_RESULT.keywords'),
            'study_course' => $listenerCourse,
            'study_test' => $listenerTest
        );
        return View::make(Helper::acclayout('study-test-result'),$page_data);

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