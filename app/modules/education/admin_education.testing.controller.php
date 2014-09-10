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
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.AdminEducationChaptersController::$name.'/{chapter}/'.$class::$name, $class,
                array(
                    'except' => array('show','create','store','edit','update'),
                    'names' => array(
                        'index'   => self::$entity.'.index',
                        'destroy' => self::$entity.'.destroy',
                    )
                )
            );
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

    public function __construct(CoursesTests $test){

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

    public function index($direction_id,$course_id,$chapter_id){

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;

        if ($chapter_id == 0):
            if (!$test = $this->course->test()->first()):
                $input = array('course_id'=>$course_id,'chapter_id'=>$chapter_id,'order'=>0,'title'=>'Итоговое тестирование по курсу "'.$this->course->title.'"','description'=>'','active'=>1);
                $test = CoursesTests::create($input);
            endif;
        else:
            if (!$test = $this->chapter->test()->first()):
                $input = array('course_id'=>$course_id,'chapter_id'=>$chapter_id,'order'=>0,'title'=>'Промежуточное тестирование','description'=>'','active'=>1);
                $test = CoursesTests::create($input);
            endif;
        endif;
        $test = $this->test->where('id',$test->id)->with(array('questions'=>function($question_query){
            $question_query->orderBy('order');
            $question_query->with(array('answers'=>function($answer_query){
                $answer_query->orderBy('order');
            }));
        }))->first();
        return View::make($this->module['tpl'].'index', compact('direction','course','chapter','test'));
    }

    public function destroy($direction_id,$course_id,$chapter_id,$test_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'','redirect'=>FALSE);
        $this->test->find($test_id)->answers()->delete();
        $this->test->find($test_id)->questions()->delete();
        $this->test->find($test_id)->delete();
        $json_request['redirect'] = URL::route('modules.index',array('direction'=>$this->direction->id,'course'=>$this->course->id));
        $json_request['responseText'] = self::$entity_name.' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }
}