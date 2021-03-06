<?php

class AdminEducationDirectionsController extends BaseController {

    public static $name = 'directions';
    public static $group = 'education';
    public static $entity = 'directions';
    public static $entity_name = 'Направление обучения';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
            Route::post($class::$group.'/'.self::$name.'/ajax-order-save', array('as' => $class::$name.'.order', 'uses' => $class."@postAjaxOrderSave"));
            Route::resource($class::$group."/".self::$name, $class,
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

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return NULL;
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return NULL;
    }

    /****************************************************************************/

    protected $direction;

    public function __construct(Directions $direction){

        $this->direction = $direction;
        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin.directions'),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function index() {

        Allow::permission($this->module['group'], 'view');
        $directions = Directions::with('courses')->orderBy('order')->with('courses')->get();
        return View::make($this->module['tpl'].'index', compact('directions'));
    }

    public function create() {
        Allow::permission($this->module['group'], 'create');
        return View::make($this->module['tpl'].'create');
    }

    public function edit($id){

        Allow::permission($this->module['group'], 'edit');
        if($direction = $this->direction->where('id',$id)->first()):
            return View::make($this->module['tpl'].'edit', compact('direction'));
        else:
            App::abort(404);
        endif;
    }

    public function store(){
        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'create');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Directions::$rules);
        if($validation->passes()):
            $insert = Input::all();
            if (Input::has('use_discount') === FALSE):
                $insert['use_discount'] = 0;
            endif;
            if (Input::has('in_progress') === FALSE):
                $insert['in_progress'] = 0;
            endif;
            $direction = $this->direction->create($insert);
            $json_request['responseText'] = self::$entity_name." добавлено";
            $json_request['redirect'] = URL::route('directions.index');
            $json_request['status'] = TRUE;
            Event::fire(Route::currentRouteName(), array(array('title'=>$direction->title)));
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function update($id){

        if(!Request::ajax()) return App::abort(404);
        Allow::permission($this->module['group'], 'edit');
        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE, 'gallery'=>0);
        $validation = Validator::make(Input::all(), Directions::$rules);
        if($validation->passes()):
            if($direction = $this->direction->where('id',$id)->first()):
                $update = Input::all();
                if (Input::has('active') === FALSE):
                    $update['active'] = 0;
                endif;
                if (Input::has('in_progress') === FALSE):
                    $update['in_progress'] = 0;
                endif;
                if (Input::has('use_discount') === FALSE):
                    $update['use_discount'] = 0;
                endif;
                $direction->update($update);
                $json_request['responseText'] = self::$entity_name." сохранен";
                $json_request['redirect'] = URL::route('directions.index');
                $json_request['status'] = TRUE;
                Event::fire(Route::currentRouteName(), array(array('title'=>$direction->title)));
            endif;
        else:
            $json_request['responseText'] = 'Неверно заполнены поля';
            $json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
        endif;
        return Response::json($json_request, 200);
    }

    public function destroy($id){

        Allow::permission($this->module['group'], 'delete');
        if(!Request::ajax()) return App::abort(404);
        $json_request = array('status'=>FALSE, 'responseText'=>'');
        Directions::findOrFail($id)->courses()->delete();
        $direction = Directions::findOrFail($id);
        Event::fire(Route::currentRouteName(), array(array('title'=>$direction->title)));
        $direction->delete();
        $json_request['responseText'] = self::$entity_name.' удален';
        $json_request['status'] = TRUE;
        return Response::json($json_request, 200);
    }

    public function postAjaxOrderSave() {

        foreach(Directions::whereIn('id', Input::get('poss'))->get() as $pl):
            $pl->order = array_search($pl->id, Input::get('poss'))+1;
            $pl->save();
        endforeach;
        return Response::make('ok');
    }
}