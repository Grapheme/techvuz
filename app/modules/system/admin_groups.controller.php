<?php

class AdminGroupsController extends BaseController {

    public static $name = 'groups';
    public static $group = 'system';
    public static $entity = 'group';
    public static $entity_name = 'группа';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;
        Route::group(array('before'=>'auth', 'prefix'=>'admin'), function() use ($class, $name, $group) {
        	Route::controller($group . '/' . $name, $class);
        });
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }
    
    /****************************************************************************/

	public function __construct(){

		#$this->beforeFilter('groups');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$name,
            'tpl'  => static::returnTpl('admin/' . self::$name),
            'gtpl' => static::returnTpl(),

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,

            'class' => __CLASS__,
        );
        View::share('module', $this->module);
	}

	public function getIndex(){

        Allow::permission($this->module['group'], 'groups');

		$groups = Group::all();

		return View::make($this->module['tpl'].'index', compact('groups'));
	}

    /****************************************************************************/

	public function getCreate(){

        Allow::permission($this->module['group'], 'groups');

		return View::make($this->module['tpl'].'create', array());
	}

	public function postStore(){

        Allow::permission($this->module['group'], 'groups');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		
		$input = array(
            'name' => mb_strtolower(Input::get('name')),
            'desc' => Input::get('desc'),
            'dashboard' => Input::get('dashboard'),
        );

		$validation = Validator::make($input, Group::$rules);
		if($validation->passes()) {

			Group::create($input);
			#return link::auth('groups');

			$json_request['responseText'] = "Группа &laquo;" . $input['desc'] . "&raquo; создана";
			#$json_request['responseText'] = print_r(Input::get('actions'), 1);
			$json_request['redirect'] = link::auth('groups');
			$json_request['status'] = TRUE;

		} else {
			#return Response::json($v->messages()->toJson(), 400);
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(),'<br />');
		}
		return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function getEdit($id){

        Allow::permission($this->module['group'], 'groups');

        if ($id == 1)
            Redirect(link::auth($this->module['rest']));

        $groups = Group::all();

		$group = Group::find($id);
        $mod_actions = Config::get('mod_actions');
        $mod_info = Config::get('mod_info');

        #Helper::dd($mod_actions);
        #Helper::dd($mod_info);
        
        $group_actions = Action::where('group_id', $group->id)->get();
        #$actions = $group->actions();
        
        $actions = array();
        foreach ($group_actions as $action) {
            #Helper::d($action->status);
            #continue;
            if ($action->status)
                $actions[$action->module][$action->action] = $action->status;
        }
        #Helper::dd($actions);
        $group_actions = $actions;
        
		return View::make($this->module['tpl'].'edit', compact('groups', 'group', 'mod_actions', 'mod_info', 'group_actions'));
	}

	public function postUpdate($group_id){

        Allow::permission($this->module['group'], 'groups');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		if(!Request::ajax())
            return App::abort(404);        

		if(!$group = Group::find($group_id)) {
			$json_request['responseText'] = 'Запрашиваемая группа не найдена!';
			return Response::json($json_request, 400);
		}
        
		$validation = Validator::make(Input::all(), Group::$rules_update);
		if($validation->passes()):

            ## Update group
			$group->name = mb_strtolower(Input::get('name'));
			$group->desc = Input::get('desc');
            $group->dashboard = Input::get('dashboard');
            $group->start_url = Input::get('start_url');
			$group->save();
			$group->touch();
			
            ## Update actions
            Action::where('group_id', $group_id)->delete();
            if (is_array(Input::get('actions')) && count(Input::get('actions'))) {
                foreach (Input::get('actions') as $module_name => $actions) {
                    foreach ($actions as $a => $act) {
                        $action = new Action;
                        $action->group_id = $group_id;
                        $action->module = $module_name;
                        $action->action = $act;
                        $action->status = 1;
                        $action->save();
                    }
                }
            }
            
			$json_request['responseText'] = 'Группа обновлена';
			#$json_request['responseText'] = print_r($group_id, 1);
			#$json_request['responseText'] = print_r($group, 1);
			#$json_request['responseText'] = print_r(Input::get('actions'), 1);
			#$json_request['responseText'] = print_r($group->actions(), 1);
			#$json_request['redirect'] = link::auth('groups');
			$json_request['status'] = TRUE;
		else:
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
		endif;
        
		return Response::json($json_request, 200);
	}

    /****************************************************************************/

	public function deleteDestroy($id){

        Allow::permission($this->module['group'], 'groups');

		if(!Request::ajax())
            return App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');

        if ($id == 1) {
            $json_request['responseText'] = 'Невозможно удалить группу Администраторы';
            return Response::json($json_request, 400);
        }

	    $deleted = Group::find($id)->delete();
		$json_request['responseText'] = 'Группа удалена';
		$json_request['status'] = TRUE;
		return Response::json($json_request, 200);
	}

}
