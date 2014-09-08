<?php

class AdminEducationCoursesController extends BaseController {

    public static $name = 'courses';
    public static $group = 'education';
    public static $entity = 'courses';
    public static $entity_name = 'Курс';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.$class::$name, $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => self::$entity.'.index',
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

    public function __construct(Directions $direction, Courses $course){

        $this->direction = Directions::where('id',Request::segment(4))->with('courses')->first();
        $this->course = $course;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.courses'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function index() {

        Allow::permission($this->module['group'], 'view');
        $direction = $this->direction;
        return View::make($this->module['tpl'].'index', compact('direction'));
    }

    public function create() {

        Allow::permission($this->module['group'], 'create');
        $direction = $this->direction;
        return View::make($this->module['tpl'].'create',compact('direction'));
    }

    public function store(){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Courses::$rules);
        if($validation->passes()):
            $input = self::coursesFiles();
            $this->course->create($input);
            $json_request['responseText'] = self::$entity_name." добавлен";
            $json_request['redirect'] = URL::route('courses.index',array('directions'=>$this->direction->id));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($direction_id,$course_id){

        Allow::permission($this->module['group'], 'edit');
        if($course = Courses::where('id',$course_id)->with('libraries')->with('curriculum')->with('metodical')->first()):
            $direction = $this->direction;
            return View::make($this->module['tpl'].'edit', compact('direction','course'));
        else:
            App::abort(404);
        endif;
    }

    public function update($direction_id,$course_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Courses::$rules);
        if($validation->passes()):
            if($course = $this->course->where('id',$course_id)->first()):
                $input = self::coursesFiles();
                $course->update($input);
                $json_request['responseText'] = self::$entity_name." сохранен";
                $json_request['redirect'] = URL::route('courses.index',array('directions'=>$this->direction->id));
                $json_request['status'] = TRUE;
            endif;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($direction_id,$course_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');

        $libraries = Courses::find($course_id)->libraries()->first();
        $curriculum = Courses::find($course_id)->curriculum()->first();
        $metodical = Courses::find($course_id)->metodical()->first();

        if (!empty($libraries) && File::exists(public_path($libraries->path))):
            File::delete(public_path($libraries->path));
            Upload::find($libraries->id)->delete();
        endif;
        if (!empty($curriculum) && File::exists(public_path($curriculum->path))):
            File::delete(public_path($curriculum->path));
            Upload::find($curriculum->id)->delete();
        endif;
        if (!empty($metodical) && File::exists(public_path($metodical->path))):
            File::delete(public_path($metodical->path));
            Upload::find($metodical->id)->delete();
        endif;

        Courses::find($course_id)->delete();

        $json_request['responseText'] = self::$entity_name.' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function coursesFiles(){

        $input['direction_id'] = Input::get('direction_id');
        $input['order'] = Input::get('order');
        $input['code'] = Input::get('code');
        $input['title'] = Input::get('title');
        $input['description'] = Input::get('description');
        $input['price'] = Input::get('price');
        $input['hours'] = Input::get('hours');

        if(Input::hasFile('libraries.file')):
            $input['libraries'] = ExtForm::process('upload', Input::file('libraries'));
        endif;
        if(Input::hasFile('curriculum.file')):
            $input['curriculum'] = ExtForm::process('upload', Input::file('curriculum'));
        endif;
        if(Input::hasFile('metodical.file')):
            $input['metodical'] = ExtForm::process('upload', Input::file('metodical'));
        endif;
        return $input;
    }
}