<?php

class AdminEducationTestsQuestionsController extends BaseController {

    public static $name = 'questions';
    public static $group = 'education';
    public static $entity = 'questions';
    public static $entity_name = 'Тестирование. Вопрос';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.AdminEducationChaptersController::$name.'/{chapter}/'.AdminEducationTestingController::$name.'/{tests}/'.$class::$name, $class,
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
    protected $question;

    public function __construct(CoursesTestsQuestions $question){

        $this->direction = Directions::findOrFail(Request::segment(4));
        $this->course = Courses::findOrFail(Request::segment(6));
        $this->chapter = Chapter::find(Request::segment(8));
        $this->test = CoursesTests::findOrFail(Request::segment(10));
        $this->question = $question;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.tests.questions'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function create($direction_id,$course_id,$chapter_id,$test_id) {

        Allow::permission($this->module['group'], 'create');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;
        $test = $this->test;
        return View::make($this->module['tpl'].'create',compact('direction','course','chapter','test'));
    }

    public function store($direction_id,$course_id,$chapter_id,$test_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), CoursesTestsQuestions::$rules);
        if($validation->passes()):
            $this->question->create(Input::all());
            $json_request['responseText'] = self::$entity_name." добавлен";
            if(is_null($this->chapter)):
                $chapter_id = 0;
            else:
                $chapter_id = $this->chapter->id;
            endif;
            $json_request['redirect'] = URL::route('testing.index',array('directions'=>$this->direction->id,'course'=>$this->course->id,'chapter'=>$chapter_id));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($direction_id,$course_id,$chapter_id,$test_id,$question_id){

        Allow::permission($this->module['group'], 'edit');
        if($question = $this->question->findOrFail($question_id)):
            $direction = $this->direction;
            $course = $this->course;
            $chapter = $this->chapter;
            $test = $this->test;
            return View::make($this->module['tpl'].'edit', compact('direction','course','chapter','test','question'));
        else:
            App::abort(404);
        endif;
    }

    public function update($direction_id,$course_id,$chapter_id,$test_id,$question_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), CoursesTestsQuestions::$rules);
        if($validation->passes()):
            if($question = $this->question->where('id',$question_id)->first()):
                $question->update(Input::all());
                $json_request['responseText'] = self::$entity_name." сохранен";
                if(is_null($this->chapter)):
                    $chapter_id = 0;
                else:
                    $chapter_id = $this->chapter->id;
                endif;
                $json_request['redirect'] = URL::route('testing.index',array('directions'=>$this->direction->id,'course'=>$this->course->id,'chapter'=>$chapter_id));
                $json_request['status'] = TRUE;
            endif;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($direction_id,$course_id,$chapter_id,$test_id,$question_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        $this->question->find($question_id)->answers()->delete();
        $this->question->find($question_id)->delete();
        $json_request['responseText'] = self::$entity_name.' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }
}