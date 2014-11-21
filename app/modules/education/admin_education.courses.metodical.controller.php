<?php

class AdminEducationCoursesMetodicalController extends BaseController {

    public static $name = 'metodical';
    public static $group = 'education';
    public static $entity = 'metodical';
    public static $entity_name = 'Специализированная документация';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::post($class::$group.'/'.self::$name.'/ajax-order-save', array('as' => $class::$name.'.order', 'uses' => $class."@postAjaxOrderSave"));
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/course/{course}/'.$class::$name, $class,
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
    protected $metodical;

    public function __construct(CourseMetodical $metodical){

        $this->direction = Directions::where('id',Request::segment(4))->with('courses')->first();
        $this->course = Courses::where('id',Request::segment(6))->with(array('metodicals'=>function($query){ $query->orderBy('order');}))->first();
        $this->metodical = $metodical;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.courses.metodical'),
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
        $course = $this->course;
        return View::make($this->module['tpl'].'index', compact('direction','course'));
    }

    public function create() {

        Allow::permission($this->module['group'], 'create');
        $direction = $this->direction;
        $course = $this->course;
        return View::make($this->module['tpl'].'create',compact('direction','course'));
    }

    public function store($direction_id,$course_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
        $validation = Validator::make(Input::all(), CourseMetodical::$rules);
        if($validation->passes()):
            $input = self::сourseMetodicalFiles();
            $this->metodical->create($input);
            $json_request['responseText'] = "Документ добавлен";
            $json_request['redirect'] = URL::route('metodical.index',array('directions'=>$this->direction->id,'course'=>$this->course->id));
            $json_request['status'] = TRUE;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($direction_id,$course_id,$metodical_id){

        Allow::permission($this->module['group'], 'edit');
        if($metodical = CourseMetodical::where('id',$metodical_id)->with('document')->first()):
            $direction = $this->direction;
            $course = $this->course;
            return View::make($this->module['tpl'].'edit', compact('direction','course','metodical'));
        else:
            App::abort(404);
        endif;
    }

    public function update($direction_id,$course_id,$metodical_id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
        $validation = Validator::make(Input::all(), CourseMetodical::$rules);
        if($validation->passes()):
            if($metodical = $this->metodical->where('id',$metodical_id)->first()):
                $input = self::сourseMetodicalFiles();
                $metodical->update($input);
                $json_request['responseText'] = "Документ сохранен";
                $json_request['redirect'] = URL::route('metodical.index',array('directions'=>$this->direction->id,'course'=>$this->course->id));
                $json_request['status'] = TRUE;
            endif;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($direction_id,$course_id,$metodical_id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        $file = $this->metodical->where('id',$metodical_id)->first()->document()->first();
        if (!empty($file) && File::exists(public_path($file->path))):
            File::delete(public_path($file->path));
            Upload::find($file->id)->delete();
        endif;
        $this->metodical->find($metodical_id)->delete();
        $json_request['responseText'] = 'Документ удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    private function сourseMetodicalFiles(){

        $input['course_id'] = Input::get('course_id');
        $input['order'] = Input::get('order');
        $input['title'] = Input::get('title');
        $input['description'] = Input::get('description');
        $input['document_id'] = ExtForm::process('upload', @Input::all()['document_id']);
        return $input;
    }

    public function postAjaxOrderSave() {

        foreach(CourseMetodical::whereIn('id', Input::get('poss'))->get() as $pl):
            $pl->order = array_search($pl->id, Input::get('poss'))+1;
            $pl->save();
        endforeach;
        return Response::make('ok');
    }
}