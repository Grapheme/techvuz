<?php

class AdminEducationModulesController extends BaseController {

    public static $name = 'modules';
    public static $group = 'education';
    public static $entity = 'modules';
    public static $entity_name = 'Модули';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function () use ($class) {
            Route::post($class::$group . '/' . self::$name . '/lectures/ajax-order-save', array('as' => $class::$name . '.lectures.order',
                'uses' => $class . "@postAjaxOrderSave"));
            Route::resource(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . $class::$name, $class,
                array(
                    'except' => array('show', 'create', 'store', 'edit', 'update', 'destroy'),
                    'names' => array(
                        'index' => self::$entity . '.index',
                    )
                )
            );
            Route::post(AdminEducationDirectionsController::$group . '/' . AdminEducationDirectionsController::$name . '/{direction}/' . AdminEducationCoursesController::$name . '/{course}/' . $class::$name . '/dublicate', array('before' => 'csrf',
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

    public function __construct() {

        $this->direction = Directions::where('id', Request::segment(4))->with('courses')->first();
        $this->course = Courses::where('id', Request::segment(6))->with(array('chapters' => function ($query) {
            $query->orderBy('order');
            $query->with(array('lectures' => function ($query_lecture) {
                $query_lecture->orderBy('order');
            }));
            $query->with('test');
        }))->with('test', 'trial_test')->first();

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.modules'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index() {

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        $course = $this->course;
        return View::make($this->module['tpl'] . 'index', compact('direction', 'course'));
    }


    /**
     * @param $direction_id
     * @param $course_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDublicate($direction_id, $course_id) {

        $validation = Validator::make(Input::all(), array('course_id' => 'required'));
        if ($validation->passes()):
            if (Courses::where('id', Input::get('course_id'))->exists()):
                foreach (Chapter::where('course_id', $course_id)->with('lectures', 'test.questions.answers')->get() as $chapter):
                    $chapter_new = Chapter::create(array('course_id' => Input::get('course_id'),
                        'order' => $chapter->order, 'title' => $chapter->title, 'description' => $chapter->description,
                        'hours' => $chapter->hours));
                    foreach ($chapter->lectures as $lecture):
                        Lectures::create(array('course_id' => Input::get('course_id'), 'chapter_id' => $chapter_new->id,
                            'order' => $lecture->order, 'title' => $lecture->title,
                            'description' => $lecture->description, 'hours' => $lecture->hours, 'document' => 0));
                    endforeach;
                    if (!empty($chapter->test)):
                        $test_new = CoursesTests::create(array('course_id' => Input::get('course_id'),
                            'chapter_id' => $chapter_new->id, 'order' => $chapter->test->order,
                            'title' => $chapter->test->title, 'active' => $chapter->test->active));
                        foreach ($chapter->test->questions as $question):
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
                endforeach;
                foreach (CoursesTests::where('course_id', $course_id)->where('chapter_id', 0)->with('questions.answers')->get() as $test):
                    $test_new = CoursesTests::create(array('course_id' => Input::get('course_id'),
                        'chapter_id' => $test->chapter_id, 'order' => $test->order, 'title' => $test->title,
                        'active' => $test->active));
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
                endforeach;
            endif;
        endif;
        return Redirect::back()->with('message', 'Модуль скопирован успешно.');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function postAjaxOrderSave() {

        foreach (Lectures::where('chapter_id', Input::get('chapter'))->whereIn('id', Input::get('poss'))->get() as $pl):
            $pl->order = array_search($pl->id, Input::get('poss')) + 1;
            $pl->save();
        endforeach;
        return Response::make('ok');
    }
}