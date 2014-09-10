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
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.AdminEducationChaptersController::$name.'/{chapter}/'.AdminEducationTestingController::$name.'/{tests}/'.AdminEducationTestsQuestionsController::$name.'/{question}/'.$class::$name, $class,
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
    protected $answer;

    public function __construct(CoursesTestsAnswers $answer){

        $this->direction = Directions::findOrFail(Request::segment(4));
        $this->course = Courses::findOrFail(Request::segment(6));
        $this->chapter = Chapter::find(Request::segment(8));
        $this->test = CoursesTests::findOrFail(Request::segment(10));
        $this->question = CoursesTestsQuestions::findOrFail(Request::segment(12));
        $this->answer = $answer;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.tests.answers'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function create($direction_id,$course_id,$chapter_id,$test_id,$question_id) {

        Allow::permission($this->module['group'], 'create');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;
        $test = $this->test;
        $question = $this->question;
        return View::make($this->module['tpl'].'create',compact('direction','course','chapter','test','question'));
    }

    public function store($direction_id,$course_id,$chapter_id,$test_id,$question_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), CoursesTestsAnswers::$rules);
        if($validation->passes()):
            $this->answer->create(Input::all());
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

    public function edit($direction_id,$course_id,$chapter_id,$test_id,$question_id,$answer_id){

        Allow::permission($this->module['group'], 'edit');
        if($answer = $this->answer->findOrFail($answer_id)):
            $direction = $this->direction;
            $course = $this->course;
            $chapter = $this->chapter;
            $test = $this->test;
            $question = $this->question;
            return View::make($this->module['tpl'].'edit', compact('direction','course','chapter','test','question','answer'));
        else:
            App::abort(404);
        endif;
    }

    public function update($direction_id,$course_id,$chapter_id,$test_id,$question_id,$answer_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), CoursesTestsAnswers::$rules);
        if($validation->passes()):
            if($answer = $this->answer->where('id',$answer_id)->first()):
                $input = Input::all();
                $input['correct'] = (int) Input::get('correct');
                $answer->update($input);
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

    public function destroy($direction_id,$course_id,$chapter_id,$test_id,$question_id,$answer_id){

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