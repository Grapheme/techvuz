<?php

class AdminEducationChaptersController extends BaseController {

    public static $name = 'chapters';
    public static $group = 'education';
    public static $entity = 'chapters';
    public static $entity_name = 'Главы';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::resource(AdminEducationDirectionsController::$group.'/'.AdminEducationDirectionsController::$name.'/{direction}/'.AdminEducationCoursesController::$name.'/{course}/'.$class::$name, $class,
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

    public function __construct(Directions $direction, Courses $course, Chapter $chapter){

        $this->direction = Directions::where('id',Request::segment(4))->with('courses')->first();
        $this->course = Courses::where('id',Request::segment(6))->with('chapters.lectures')->first();
        $this->chapter = $chapter;

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.chapters'),
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
        return View::make($this->module['tpl'].'create',compact('direction','course'));
    }

    public function store(){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Chapter::$rules);
        if($validation->passes()):
            $chapter = $this->chapter->create(Input::all());
            $json_request['responseText'] = self::$entity_name." добавлена";
            $json_request['redirect'] = URL::route('modules.index',array('directions'=>$this->direction->id,'course'=>$this->course->id));
            $json_request['status'] = TRUE;
            Event::fire(Route::currentRouteName(), array(array('title'=>$chapter->title.'. Курс: '.$this->course->title)));
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
                Event::fire(Route::currentRouteName(), array(array('title'=>$chapter->title.'. Курс: '.$this->course->title)));
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
        if(Chapter::find($chapter_id)->test()->exists()):
            Chapter::find($chapter_id)->test()->first()->answers()->delete();
            Chapter::find($chapter_id)->test()->first()->questions()->delete();
            Chapter::find($chapter_id)->test()->delete();
        endif;
        $chapter = Chapter::findOrFail($chapter_id);
        Event::fire(Route::currentRouteName(), array(array('title'=>$chapter->title.'. Курс: '.$this->course->title)));
        $chapter->delete();
        $json_request['responseText'] = self::$entity_name.' удалена';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }
}