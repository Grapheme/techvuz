<?php

class TagsController extends BaseController {

    public static $name = 'admin_48hoursActions';
    public static $group = '48hours';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        #echo $prefix;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::controller($class::$group."/actions", $class);
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
        ##
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
        	'view'   => 'Просмотр',
        	'create' => 'Создание',
        	'edit'   => 'Редактирование',
        	'delete' => 'Удаление',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        ##
    }
        
    /****************************************************************************/
    
	public function __construct(){

		$this->beforeFilter('48hours');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group."/actions",
            'tpl' => static::returnTpl('admin/actions'),
            'gtpl' => static::returnTpl(),
        );

        View::share('module', $this->module);

        #View::share('module_name', self::$name);
        #View::share('group_name', self::$group);
        #$this->tpl = static::returnTpl('admin/actions');
        #$this->gtpl = static::returnTpl();
        #View::share('module_tpl', $this->tpl);
        #View::share('module_gtpl', $this->gtpl);
	}

	public function getIndex(){
		
		$actions = Action::orderBy('id', 'desc')->get();
		return View::make($this->module['tpl'].'index', compact('actions'));
	}

    /************************************************************************************/

	public function getCreate(){

		return View::make($this->module['tpl'].'create');
	}

	public function postStore(){

		if(!Request::ajax())
            return App::abort(404);
            
		$input = Input::all();
        $input['date_time'] = date("Y-m-d", strtotime(Input::get('date_time')));

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		$validator = Validator::make($input, Action::$rules);
		if($validator->passes()) {

		    #$json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
		    #return Response::json($json_request,200);

			#self::saveNewsModel();
            $id = Action::create($input)->id;
			$json_request['responseText'] = 'Мероприятие создано';
			$json_request['redirect'] = link::auth( $this->module['rest'] );
			$json_request['status'] = TRUE;
		} else {
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = $validator->messages()->all();
		}
		return Response::json($json_request,200);
	}

    /************************************************************************************/
    
	public function getEdit($id){
		
		$action = Action::findOrFail($id);
		return View::make($this->module['tpl'].'edit', compact('action'));
	}

	public function postUpdate($id){

		if(!Request::ajax())
            return App::abort(404);

		$input = Input::all();
        $input['date_time'] = date("Y-m-d", strtotime(Input::get('date_time')));

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		$validator = Validator::make($input, Action::$rules);
		if($validator->passes()) {
            $place = Action::find($id)->update($input);
            #$place->update($input);
			$json_request['responseText'] = 'Мероприятие обновлено.';
			#$json_request['redirect'] = link::auth( $this->module['rest'] );
			$json_request['status'] = TRUE;
		} else {
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = $validator->messages()->all();
		}
		return Response::json($json_request, 200);
	}

    /************************************************************************************/

	public function deleteDestroy($id){

		if(!Request::ajax())
            return App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');
	    $deleted = Place::find($id)->delete();
		$json_request['responseText'] = 'Мероприятие удалено';
		$json_request['status'] = TRUE;
		return Response::json($json_request,200);
	}

}


