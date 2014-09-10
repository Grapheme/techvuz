<?php

class AdminEducationTestsAnswersController extends BaseController {

    public static $name = 'answers';
    public static $group = 'education';
    public static $entity = 'answers';
    public static $entity_name = 'Тестирование. Ответ';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.AdminEducationTestingController::$name.'/{test}/'.$class::$name, $class,
                array(
                    'except' => array('show','index'),
                    'names' => array(
                        'create'  => self::$entity.'.create',
                        'store'   => self::$entity.'.store',
                        'edit'    => self::$entity.'.edit',
                        'update'  => self::$entity.'.update',
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
    protected $answer;

    public function __construct(CoursesTestsAnswers $answer){

        $this->direction = Directions::findOrFail(Request::segment(4));
        $this->course = Courses::findOrFail(Request::segment(6));
        $this->chapter = Chapter::find(Request::segment(8));
        $this->test = CoursesTests::findOrFail(Request::segment(10));
        $this->answer = $answer;

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
        return View::make($this->module['tpl'].'index', compact('direction','course','chapter','test'));
    }

    public function destroy($direction_id,$course_id,$chapter_id,$lecture_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        $file = $this->lecture->where('id',$lecture_id)->first()->document()->first();
        if (!empty($file) && File::exists(public_path($file->path))):
            File::delete(public_path($file->path));
            Upload::find($file->id)->delete();
        endif;
        $this->lecture->find($lecture_id)->delete();
        $json_request['responseText'] = self::$entity_name.' удалена';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }
}