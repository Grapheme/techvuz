<?php

class AdminEducationTestingController extends BaseController {

    public static $name = 'testing';
    public static $group = 'education';
    public static $entity = 'testing';
    public static $entity_name = 'Тест';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function () use ($class) {
            Route::post(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . AdminEducationChaptersController::$name . '/{chapter}/' . $class::$name . '/ajax-order-save', array('as' => $class::$name . '.order',
                'uses' => $class . "@postAjaxOrderSave"));
            Route::resource(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . AdminEducationChaptersController::$name . '/{chapter}/' . $class::$name, $class,
                array(
                    'except' => array('show', 'create', 'store', 'edit', 'update'),
                    'names' => array(
                        'index' => self::$entity . '.index',
                        'destroy' => self::$entity . '.destroy',
                    )
                )
            );
            Route::resource(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . AdminEducationChaptersController::$name . '/{chapter}/trial', $class,
                array(
                    'except' => array('show', 'create', 'store', 'edit', 'update'),
                    'names' => array(
                        'index' => 'trial_testing.index',
                        'destroy' => 'trial_testing.destroy'
                    )
                )
            );
            Route::post(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . AdminEducationChaptersController::$name . '/{chapter}/' . $class::$name . '/dublicate', array('before' => 'csrf',
                'as' => $class::$name . '.dublicate', 'uses' => $class . "@postDublicate"));
        });
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

    protected $direction;
    protected $course;
    protected $chapter;
    protected $test;

    public function __construct(CoursesTests $test) {

        $this->direction = Directions::findOrFail(Request::segment(4));
        $this->course = Courses::findOrFail(Request::segment(6));
        $this->chapter = Chapter::find(Request::segment(8));
        $this->test = $test;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.tests'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function index($direction_id, $course_id, $chapter_id) {

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;

        if (Request::segment(9) == 'trial'):
            if (!$test = $this->course->trial_test()->first()):
                $input = array('course_id' => $course_id, 'chapter_id' => $chapter_id, 'order' => 0,
                    'title' => 'Пробное тестирование', 'description' => '', 'active' => 1, 'trial_test' => 1);
                $test = CoursesTests::create($input);
            endif;
        else:
            if ($chapter_id == 0):
                if (!$test = $this->course->test()->first()):
                    $input = array('course_id' => $course_id, 'chapter_id' => $chapter_id, 'order' => 0,
                        'title' => 'Итоговое тестирование', 'description' => '', 'active' => 1, 'trial_test' => 0);
                    $test = CoursesTests::create($input);
                    Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
                endif;
            else:
                if (!$test = $this->chapter->test()->first()):
                    $input = array('course_id' => $course_id, 'chapter_id' => $chapter_id, 'order' => 0,
                        'title' => 'Промежуточное тестирование', 'description' => '', 'active' => 1);
                    $test = CoursesTests::create($input);
                    Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
                endif;
            endif;
        endif;
        $test = $this->test->where('id', $test->id)->with(array('questions' => function ($question_query) {
            $question_query->orderBy('order');
            $question_query->with(array('answers' => function ($answer_query) {
                $answer_query->orderBy('order');
            }));
        }))->first();
        return View::make($this->module['tpl'] . 'index', compact('direction', 'course', 'chapter', 'test'));
    }

    public function destroy($direction_id, $course_id, $chapter_id, $test_id) {

        Allow::permission($this->module['group'], 'delete');
        if (!Request::ajax()) return App::abort(404);
        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        $this->test->findOrFail($test_id)->answers()->delete();
        $this->test->findOrFail($test_id)->questions()->delete();
        $test = $this->test->findOrFail($test_id);
        Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
        $test->delete();
        $json_request['redirect'] = URL::route('modules.index', array('direction' => $this->direction->id,
            'course' => $this->course->id));
        $json_request['responseText'] = self::$entity_name . ' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function postDublicate($direction_id, $course_id, $chapter_id) {

        $validation = Validator::make(Input::all(), array('course_id' => 'required', 'chapter_id' => 'required'));
        if ($validation->passes()):
            $valid = FALSE;
            $test = array();
            if (Input::get('chapter_id') > 0 && Courses::where('id', Input::get('course_id'))->first()->chapters()->where('id', Input::get('chapter_id'))->exists()):
                $valid = TRUE;
            elseif (Input::get('chapter_id') == 0 && Courses::where('id', Input::get('course_id'))->exists()):
                $valid = TRUE;
            endif;
            if ($chapter_id > 0):
                $test = Chapter::where('id', $chapter_id)->first()->test()->with('questions.answers')->first();
            else:
                $test = Courses::where('id', $course_id)->first()->test()->with('questions.answers')->first();
            endif;
            if ($valid && !empty($test)):
                if (!$test_new = CoursesTests::where('course_id', Input::get('course_id'))->where('chapter_id', Input::get('chapter_id'))->first()):
                    $test_new = CoursesTests::create(array('course_id' => Input::get('course_id'),
                        'chapter_id' => Input::get('chapter_id'), 'order' => $test->order, 'title' => $test->title,
                        'active' => $test->active));
                endif;
                foreach ($test->questions as $question):
                    $testQuestion_new = CoursesTestsQuestions::create(array('test_id' => $test_new->id,
                        'order' => $question->order, 'title' => $question->title,
                        'description' => $question->description));
                    foreach ($question->answers as $answer):
                        CoursesTestsAnswers::create(array('test_id' => $test_new->id,
                            'test_question_id' => $testQuestion_new->id, 'order' => $answer->order,
                            'title' => $answer->title, 'description' => $answer->description,
                            'correct' => $answer->correct));
                    endforeach;
                endforeach;
            endif;
        endif;
        return Redirect::back()->with('message', 'Тест скопирован успешно.');
    }

    public function postAjaxOrderSave() {

        foreach (CoursesTestsAnswers::where('test_question_id', Input::get('question'))->whereIn('id', Input::get('poss'))->get() as $pl):
            $pl->order = array_search($pl->id, Input::get('poss')) + 1;
            $pl->save();
        endforeach;
        return Response::make('ok');
    }

    /******************************************************************************/

    public function trial_index($direction_id, $course_id, $chapter_id) {

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;

        if ($chapter_id == 0):
            if (!$test = $this->course->test()->first()):
                $input = array('course_id' => $course_id, 'chapter_id' => $chapter_id, 'order' => 0,
                    'title' => 'Итоговое тестирование', 'description' => '', 'active' => 1);
                $test = CoursesTests::create($input);
                Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
            endif;
        else:
            if (!$test = $this->chapter->test()->first()):
                $input = array('course_id' => $course_id, 'chapter_id' => $chapter_id, 'order' => 0,
                    'title' => 'Промежуточное тестирование', 'description' => '', 'active' => 1);
                $test = CoursesTests::create($input);
                Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
            endif;
        endif;
        $test = $this->test->where('id', $test->id)->with(array('questions' => function ($question_query) {
            $question_query->orderBy('order');
            $question_query->with(array('answers' => function ($answer_query) {
                $answer_query->orderBy('order');
            }));
        }))->first();
        return View::make($this->module['tpl'] . 'index', compact('direction', 'course', 'chapter', 'test'));
    }

    public function trial_destroy($direction_id, $course_id, $chapter_id, $test_id) {

        Allow::permission($this->module['group'], 'delete');
        if (!Request::ajax()) return App::abort(404);
        $json_request = array('status' => FALSE, 'responseText' => '', 'redirect' => FALSE);
        $this->test->findOrFail($test_id)->answers()->delete();
        $this->test->findOrFail($test_id)->questions()->delete();
        $test = $this->test->findOrFail($test_id);
        Event::fire(Route::currentRouteName(), array(array('title' => $test->title . '. Курс: ' . $this->course->title)));
        $test->delete();
        $json_request['redirect'] = URL::route('modules.index', array('direction' => $this->direction->id,
            'course' => $this->course->id));
        $json_request['responseText'] = self::$entity_name . ' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }
}