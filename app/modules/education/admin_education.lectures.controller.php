<?php

class AdminEducationLecturesController extends BaseController {

    public static $name = 'lectures';
    public static $group = 'education';
    public static $entity = 'lectures';
    public static $entity_name = 'Лекция';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.AdminEducationChaptersController::$name.'/{chapter}/'.$class::$name, $class,
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
    protected $lecture;

    public function __construct(Lectures $lecture){

        $this->direction = Directions::where('id',Request::segment(4))->with('courses')->first();
        $this->course = Courses::where('id',Request::segment(6))->with('chapters.lectures')->first();
        $this->chapter = Chapter::where('id',Request::segment(8))->first();
        $this->lecture = $lecture;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.lectures'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function create() {

        Allow::permission($this->module['group'], 'create');
        $direction = $this->direction;
        $course = $this->course;
        $chapter = $this->chapter;
        return View::make($this->module['tpl'].'create',compact('direction','course','chapter'));
    }

    public function store(){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Lectures::$rules);
        if($validation->passes()):
            $input = self::lectureFiles();
            $this->lecture->create($input);
            $json_request['responseText'] = self::$entity_name." добавлена";
            $json_request['redirect'] = URL::route('modules.index',array('directions'=>$this->direction->id,'course'=>$this->course->id));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($direction_id,$course_id,$chapter_id){

        Allow::permission($this->module['group'], 'edit');
        if($chapter = Chapter::where('id',$chapter_id)->first()):
            $direction = $this->direction;
            $course = $this->course;
            return View::make($this->module['tpl'].'edit', compact('direction','course','chapter'));
        else:
            App::abort(404);
        endif;
    }

    public function update($direction_id,$course_id,$chapter_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Chapter::$rules);
        if($validation->passes()):
            if($chapter = $this->chapter->where('id',$chapter_id)->first()):
                $chapter->update(Input::all());
                $json_request['responseText'] = self::$entity_name." сохранена";
                $json_request['redirect'] = URL::route('modules.index',array('directions'=>$this->direction->id,'course'=>$this->course->id));
                $json_request['status'] = TRUE;
            endif;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($direction_id,$course_id,$chapter_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        Chapter::find($chapter_id)->delete();
        $json_request['responseText'] = self::$entity_name.' удалена';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function lectureFiles(){

        $input['course_id'] = Input::get('course_id');
        $input['chapter_id'] = Input::get('chapter_id');
        $input['order'] = Input::get('order');
        $input['title'] = Input::get('title');
        $input['description'] = Input::get('description');

        if(Input::hasFile('document.file')):
            $input['document'] = ExtForm::process('upload', Input::file('document'));
        endif;
        return $input;
    }
}