<?php

class AdminUsersController extends BaseController {

    public static $name = 'users';
    public static $group = 'system';
    public static $entity = 'user';
    public static $entity_name = 'юзер';

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
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Пользователи', 
            'visible' => 0,
        );
    }
    
    /****************************************************************************/
    
	public function __construct(User $user){
		
		$this->beforeFilter('users');

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

        Allow::permission($this->module['name'], 'view');

        ## Фильтр юзеров по группе
        $group = false;
        $group_id = Input::get('group_id');
        $group_name = Input::get('group');

        ## Обрабатываем условия фильтра
        if ($group_id != '' && !is_null($group = Group::where('id', $group_id)->first()))
		    $users = User::where('group_id', $group->id)->get();
        elseif ($group_name != '' && !is_null($group = Group::where('name', $group_name)->first()))
		    $users = User::where('group_id', $group->id)->get();
        else
            $users = User::all();

        if (@!is_object($group))
            $group = Group::firstOrNew(array('id' => 0));

		$groups = Group::all();
        $groups_ids = array();
		foreach($groups as $grp) {
			$groups_ids[] = $grp->id;
		}
		return View::make($this->module['tpl'].'index', compact('group', 'users', 'groups', 'groups_ids'));
	}

    /****************************************************************************/

	public function getCreate(){

        Allow::permission($this->module['name'], 'create');

		$groups = Group::all();
        $groups_data = array('' => 'Выберите группу');
		foreach($groups as $grp) {
			$groups_data[$grp->id] = $grp->desc;
		}        
		return View::make($this->module['tpl'].'create', compact('groups_data'));
	}

	public function postStore(){

        Allow::permission($this->module['name'], 'create');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);

		$input = array(
            'name' => Input::get('name'),
            'group_id' => (int)Input::get('group'),
            'email' => Input::get('email'),
            'password' => Hash::make(Input::get('password1')),
        );

		$validation = Validator::make($input, User::$rules);
		if($validation->passes()) {

			$user = User::create($input);
			#return link::auth('groups');

			$json_request['responseText'] = "Пользователь добавлен";
			#$json_request['responseText'] = print_r(Input::get('actions'), 1);
			$json_request['redirect'] = link::auth($this->module['rest'].'/edit/'.$user->id);
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

        Allow::permission($this->module['name'], 'edit');

		$groups = Group::all();
        $groups_data = array('' => 'Выберите группу');
		foreach($groups as $grp) {
			$groups_data[$grp->id] = $grp->desc;
		}        

		$user = User::find($id);
		return View::make($this->module['tpl'].'edit', compact('user', 'groups', 'groups_data'));
	}

	public function postUpdate($id){

        Allow::permission($this->module['name'], 'edit');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		if(!Request::ajax())
            return App::abort(404);        

		if(!$user = User::find($id)) {
			$json_request['responseText'] = 'Пользователь не найден';
			return Response::json($json_request, 400);
		}

        $input = array(
			"name" => Input::get('name'),
			"surname" => Input::get('surname'),
			"email" => Input::get('email'),
            "active" => (int)(bool)Input::get('active'),
            "group_id" => Input::get('group_id'),
        );
        
        $rules_update = User::$rules_update;
        $rules_update['email'] .= ',' . $user->id;
        
		$validation = Validator::make($input, $rules_update);
		if($validation->passes()):

            ## Update user
			$user->update($input);
			$user->touch();
			
			$json_request['responseText'] = 'Данные пользователя изменены';
			#$json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
			#$json_request['responseText'] = "<pre>" . print_r($input, 1) . "</pre>";
			#$json_request['redirect'] = link::auth('groups');
			$json_request['status'] = TRUE;
		else:
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
		endif;
        
		return Response::json($json_request, 200);
	}
    

	public function postChangepass($id){

        Allow::permission($this->module['name'], 'edit');

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		if(!Request::ajax())
            return App::abort(404);        

		if(Input::get('password1') == '' || Input::get('password2') == '' || Input::get('password1') != Input::get('password2')) {
			$json_request['responseText'] = 'Пароли должны совпадать';
			return Response::json($json_request, 400);
		}

		if(!$user = User::find($id)) {
			$json_request['responseText'] = 'Пользователь не найден';
			return Response::json($json_request, 400);
		}

        $input = array(
            'password' => Hash::make(Input::get('password1')),
        );
        
		$validation = Validator::make($input, User::$rules_changepass);
		if($validation->passes()):

            ## Update user
			$user->update($input);
			$user->touch();
			
			$json_request['responseText'] = 'Пароль пользователя успешно сменен';
			#$json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
			#$json_request['responseText'] = "<pre>" . print_r($input, 1) . "</pre>";
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

        Allow::permission($this->module['name'], 'delete');

		if(!Request::ajax())
            return App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');
	    $deleted = User::find($id)->delete();
		$json_request['responseText'] = 'Пользователь удален';
		$json_request['status'] = TRUE;
		return Response::json($json_request, 200);
	}
    
    /****************************************************************************/

}
