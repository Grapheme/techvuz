<?php

class PublicEducationCoursesController extends BaseController {

    public static $name = 'courses';
    public static $group = 'education';
    public static $entity = 'courses';
    public static $entity_name = 'Курс';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::get('/katalog-kursov-dlya-sro/{url}', array('as' => 'course-page', 'uses' => $class . '@index'));
        Route::get('/katalog-kursov-dlya-sro/{url}/trial', array('as' => 'course-page-trial-test',
            'uses' => $class . '@trial_test'));
        Route::post('/katalog-kursov-dlya-sro/{url}/trial', array('before' => 'csrf',
            'as' => 'trial-test-finish', 'uses' => $class . '@trialTestFinish'));
        Route::get('/katalog-kursov-dlya-sro/{url}/trial/result', array('as' => 'course-page-trial-test-result',
            'uses' => $class . '@trialTestResult'));
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

    protected $course;

    public function __construct(Courses $course) {

        $this->course = $course;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }

    public function index($url = FALSE) {

        if (!@$url)
            $url = Input::get('url');
        if ($course_seo = Seo::where('module', 'education-courses')->where('url', $url)->first()):
            $course = $this->course->whereActive(TRUE)->where('id', $course_seo->unit_id)
                ->with('direction', 'seo', 'trial_test')->first();
            if ($course):
                return View::make('templates.' . Config::get('app.template') . '.course', compact('course', 'course_seo'));
            endif;
        endif;
        App::abort(404);
    }

    public function trial_test($url = FALSE) {

        if (!$url)
            $url = Input::get('url');
        if ($course_seo = Seo::where('module', 'education-courses')->where('url', $url)->first()):
            $course = $this->course->whereActive(TRUE)->where('id', $course_seo->unit_id)->with('trial_test')->first();
            if (!empty($course->trial_test)):
                $test = CoursesTests::where('id', $course->trial_test->id)
                    ->where('active', 1)
                    ->with('questions.answers')
                    ->first();
                return View::make(Helper::layout('trial_testing.index-test'), compact('course', 'course_seo', 'test'));
            endif;
        endif;
        App::abort(404);
    }

    public function trialTestFinish($url) {

        if ($course_seo = Seo::where('module', 'education-courses')->where('url', $url)->first()):
            $course = $this->course->whereActive(TRUE)->where('id', $course_seo->unit_id)->with('trial_test')->first();
            if (!empty($course->trial_test)):
                $test = CoursesTests::where('id', $course->trial_test->id)->where('active', 1)->with('questions.answers')->first();
            else:
                App::abort(404);
            endif;
        else:
            App::abort(404);
        endif;

        $validator = Validator::make(Input::all(), array('questions' => 'required', 'time_attempt' => 'required'));
        if ($validator->passes()):
            $questions_answers = array();
            $user_questions_answers = Input::get('questions');
            foreach ($test->questions as $question):
                foreach ($question->answers as $answer):
                    if ($answer->correct == 1):
                        $questions_answers[$question->id] = $answer->id;
                    endif;
                endforeach;
            endforeach;
            $test_max_balls = count($questions_answers);
            $test_user_balls = 0;
            foreach ($questions_answers as $question_id => $answer_id):
                if (isset($user_questions_answers[$question_id]) && $user_questions_answers[$question_id] == $answer_id):
                    $test_user_balls++;
                endif;
            endforeach;
            $success_test_percent = Config::get('site.success_test_percent') ? Config::get('site.success_test_percent') : 70;
            $result_attempt = @round($test_user_balls / $test_max_balls, 3) * 100;
            if ($result_attempt >= $success_test_percent):
                return Redirect::route('course-page-trial-test-result', $course_seo->url)->with('message.text', Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.success_course_test') . ' ' . round($result_attempt, 0) . '%</h4>')->with('message.status', 'test-result')->with('message.show_result', TRUE);
            else:
                return Redirect::route('course-page-trial-test-result', $course_seo->url)->with('message.text', Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.fail') . ' ' . round($result_attempt) . '%</h4>')->with('message.status', 'test-result')->with('message.show_repeat', TRUE);
            endif;
        else:
            return Redirect::back()->with('message', Lang::get('interface.COMPANY_LISTENER_STUDY_TEST_FINISH.empty_answers'));
        endif;
    }

    public function trialTestResult($url) {

        if (!$url)
            $url = Input::get('url');
        if ($course_seo = Seo::where('module', 'education-courses')->where('url', $url)->first()):
            $course = $this->course->whereActive(TRUE)->where('id', $course_seo->unit_id)->with('trial_test')->first();
            if (!empty($course->trial_test)):
                $test = CoursesTests::where('id', $course->trial_test->id)->where('active', 1)->first();
                return View::make(Helper::layout('trial_testing.result-test'), compact('course', 'course_seo', 'test'));
            endif;
        endif;
        App::abort(404);
    }
}