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
            Route::post($class::$group.'/'.self::$name.'/ajax-order-save', array('as' => $class::$name.'.order', 'uses' => $class."@postAjaxOrderSave"));
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

    public function __construct(Courses $course){

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
        $courses = Courses::where('direction_id',$direction->id)->orderBy('order')->with('lectures','metodicals')->get();
        return View::make($this->module['tpl'].'index', compact('direction','courses'));
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
            $course = $this->course->create($input);
            self::coursesSEO($course->id);
            $json_request['responseText'] = self::$entity_name." добавлен";
            $json_request['redirect'] = URL::route('courses.index',array('directions'=>$this->direction->id));
            $json_request['status'] = TRUE;
            Event::fire(Route::currentRouteName(), array(array('title'=>$course->title)));
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function edit($direction_id,$course_id){

        Allow::permission($this->module['group'], 'edit');
        if($course = Courses::where('id',$course_id)->with('seo')->first()):
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
                self::coursesSEO($course->id);
                $json_request['responseText'] = self::$entity_name." сохранен";
                $json_request['redirect'] = URL::route('courses.index',array('directions'=>$this->direction->id));
                $json_request['status'] = TRUE;
                Event::fire(Route::currentRouteName(), array(array('title'=>$course->title)));
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
        $course = Courses::findOrFail($course_id);
        Event::fire(Route::currentRouteName(), array(array('title'=>$course->title)));
        $course->delete();
        $json_request['responseText'] = self::$entity_name.' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    private function coursesFiles(){

        if (Input::has('use_discount') === FALSE):
            $input['use_discount'] = 0;
        else:
            $input['use_discount'] = Input::get('use_discount');
        endif;

        $input['direction_id'] = Input::get('direction_id');
        $input['order'] = Input::get('order');
        $input['code'] = Input::get('code');
        $input['title'] = Input::get('title');
        $input['test_title'] = Input::get('test_title');
        $input['test_hours'] = Input::get('test_hours');
        $input['description'] = Input::get('description');
        $input['price'] = Input::get('price');
        $input['discount'] = Input::get('discount');
        $input['hours'] = Input::get('hours');
        $input['curriculum'] = Input::get('curriculum');
        $input['certificate'] = Input::get('certificate');
        if (Input::has('active') === FALSE):
            $input['active'] = 0;
        else:
            $input['active'] = 1;
        endif;
        if (Input::has('in_progress') === FALSE):
            $input['in_progress'] = 0;
        else:
            $input['in_progress'] = 1;
        endif;
        return $input;
    }

    private function coursesSEO($unit_id){

        $input = Input::all();
        $seo = Helper::withdraw($input,'seo');
        if (empty($seo['url'])):
            $seo['url'] = BaseController::stringTranslite(Input::get('title'));
        endif;
        $seo_result = ExtForm::process('seo', array(
            'module'  => 'education-courses',
            'unit_id' => $unit_id,
            'data'    => $seo,
        ));
        return $seo_result;
    }

    public function postAjaxOrderSave() {

        foreach(Courses::whereIn('id', Input::get('poss'))->get() as $pl):
            $pl->order = array_search($pl->id, Input::get('poss'))+1;
            $pl->save();
        endforeach;
        return Response::make('ok');
    }
}