<?php

class AdminMenuEditorController extends BaseController {

    public static $name = 'menu_editor';
    public static $group = 'system';
    public static $entity = 'menu';
    public static $entity_name = 'меню';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class, $name, $group) {

        	#Route::controller($group . '/' . $name, $class);

            Route::get ($group.'/' . $name . '/{menu_id}/manage', array('as' => $name.'.manage', 'uses' => $class.'@manage'));
            Route::resource($group . '/' . $name, $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => $name.'.index',
                        'create'  => $name.'.create',
                        'store'   => $name.'.store',
                        'edit'    => $name.'.edit',
                        'update'  => $name.'.update',
                        'destroy' => $name.'.destroy',
                    )
                )
            );
        });
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
    }
    
    /****************************************************************************/
    
	public function __construct(User $user){

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


	public function index(){

        Allow::permission($this->module['group'], $this->module['name']);

        $menus = Storage::where('module', 'menu')->get();
        #Helper::dd($menus);
        #$menus = json_decode($menus, 1);

        foreach ($menus as $m => $menu) {
            $menu->extract();
            $menus[$m] = $menu;
        }

		return View::make($this->module['tpl'].'index', compact('menus'));
	}

    /****************************************************************************/

	public function create(){

        Allow::permission($this->module['group'], $this->module['name']);

        $element = new Storage;

        $menu_placement = Storage::firstOrNew(array('module' => 'menu_placement', 'name' => 'menu_placement'));
        $menu_placement = json_decode($menu_placement->value, 1);
        #Helper::tad($menu_placement);

        $temp = Storage::where('module', 'menu')->get();
        $menus = array();
        foreach ($temp as $tmp)
            $menus[$tmp->name] = @json_decode($tmp->value, 1)['title'];
        #Helper::dd($menus);

        return View::make($this->module['tpl'].'edit', compact('element', 'menu_placement', 'menus'));
	}

	public function edit($id){

        Allow::permission($this->module['group'], $this->module['name']);

		$element = Storage::find($id);
        if (!is_object($element))
            App::abort(404);

        $element->extract();

        $menu_placement = Storage::firstOrNew(array('module' => 'menu_placement', 'name' => 'menu_placement'));
        $menu_placement = json_decode($menu_placement->value, 1);
        #Helper::tad($menu_placement);

        $temp = Storage::where('module', 'menu')->get();
        $menus = array();
        foreach ($temp as $tmp)
            $menus[$tmp->name] = @json_decode($tmp->value, 1)['title'];
        #Helper::dd($menus);

        return View::make($this->module['tpl'].'edit', compact('element', 'menu_placement', 'menus'));
	}

    public function manage($id){

        Allow::permission($this->module['group'], $this->module['name']);

        $element = Storage::find($id);
        if (!is_object($element))
            App::abort(404);

        $element->extract();

        #Helper::tad($element);
        #Helper::dd($element->items->{5}->title);

        /*
        Helper::dd(
            StringView::make(
                array(
                    'template'  => $element->items->{5}->title,
                    'cache_key' => sha1($element->items->{5}->title),
                    'updated_at' => time()
                ),
                array('key' => 'val')
            )->render()
        );
        */

        #Helper::dd(StringView::force($element->items->{5}->title));

        $pages = Page::where('version_of', NULL)->orderBy('created_at', 'DESC')->get();

        $functions = array();
        $temp = Config::get('menu.functions');

        if (count($temp)) {
            foreach ($temp as $name => $closure) {
                $result = $closure();
                $functions[$name] = @$result['text'] . " (" . $name . ")" ?: $name;
            }
        }

        return View::make($this->module['tpl'].'manage', compact('element', 'pages', 'functions'));
    }

    /************************************************************************************/


    public function store() {

        return $this->postSave();
    }


    public function update($id) {

        return $this->postSave($id);
    }


	public function postSave($id = false){

        Allow::permission($this->module['group'], $this->module['name']);

		$json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		if(!Request::ajax())
            App::abort(404);

        if ($id > 0 && NULL !== ($element = Storage::find($id))) {
            $exist = true;
            $rules = array();
            $redirect = false;
            $input = $element->toArray();
            $input['value'] = json_decode($input['value'], 1);
        } else {
            $exist = false;
            $rules = Storage::$rules;
            $redirect = true;
            $element = new Storage;
            $input = array('module' => 'menu');
        }

        #Helper::dd($input);
        #Helper::dd(is_null(Input::get('nesting_level2')));

        /**
         * Основные параметры
         */
        if (!is_null(Input::get('name')))
            $input['name'] = Input::get('name');
        if (!is_null(Input::get('title')))
            $input['value']['title'] = Input::get('title');
        if (!is_null(Input::get('nesting_level')))
            $input['value']['nesting_level'] = Input::get('nesting_level');

        /**
         * Дополнительные параметры
         */
        if (!is_null(Input::get('container')))
            $input['value']['container'] = Input::get('container');
        if (!is_null(Input::get('element_container')))
            $input['value']['element_container'] = Input::get('element_container');
        if (!is_null(Input::get('element')))
            $input['value']['element'] = Input::get('element');
        if (!is_null(Input::get('active_class')))
            $input['value']['active_class'] = Input::get('active_class');

        /**
         * Элементы меню и их порядок
         */
        if (!is_null(Input::get('items')))
            $input['value']['items'] = Input::get('items');
        if (!is_null(Input::get('order')))
            $input['value']['order'] = Input::get('order');

        #Helper::dd($input);

        $input['value'] = json_encode($input['value']);

        #Helper::dd($input);

        $validation = Validator::make($input, $rules);
		if($validation->passes()):

            ## CREATE OR UPDATE CURRENT MENU
            if ($exist)
                $element->update($input);
            else
                $element = $element->create($input);


            /**
             * Предустановленные места для меню
             */
            if (!is_null(Input::get('update_placements'))) {
                $placements = (array)Input::get('placements');
                #Helper::dd($placements);
                #dd($placements);
                $placements_values = array_flip($placements);
                #Helper::dd($placements_values);


                $layout_placements = Helper::getLayoutProperties();
                $layout_placements = @(array)$layout_placements['MENU_PLACEMENTS'];
                #Helper::dd($layout_placements);

                $menu_placement = Storage::firstOrNew(array('module' => 'menu_placement', 'name' => 'menu_placement'));
                #Helper::dd($menu_placement);

                $menu_placement_value = is_object($menu_placement) ? json_decode($menu_placement->value, 1) : array();

                $array = array();
                foreach ($layout_placements as $layout_placement_key => $layout_placement_value) {
                    #Helper::d("$layout_placement_key => $layout_placement_value");
                    $value = false;
                    if (isset($placements_values[$layout_placement_key])) {
                        $value = $element->name;
                    } elseif (
                        isset($menu_placement_value[$layout_placement_key])
                        && $menu_placement_value[$layout_placement_key] != $element->name
                    ) {
                        $value = $menu_placement_value[$layout_placement_key];
                    } else {
                        $value = false;
                    }
                    $array[$layout_placement_key] = $value;
                }
                #Helper::d($array);
                $menu_placement->value = json_encode($array);
                #Helper::ta($menu_placement);
                $menu_placement->save();

                #Setting::where('module', 'menu_placement')->whereIn('name', $layout_placements);
            }

			$json_request['responseText'] = 'Сохранено';
            if ($redirect && Input::get('redirect'))
                $json_request['redirect'] = Input::get('redirect');
			$json_request['status'] = TRUE;
		else:
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = implode($validation->messages()->all(), '<br />');
		endif;
        
		return Response::json($json_request, 200);
	}
    

    /****************************************************************************/


	public function destroy($id){

        Allow::permission($this->module['group'], $this->module['name']);

		if(!Request::ajax())
            App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');
	    $deleted = Storage::where('module', 'menu')->where('id', $id)->delete();
		$json_request['responseText'] = 'Удалено';
		$json_request['status'] = TRUE;
		return Response::json($json_request, 200);
	}
    
    /****************************************************************************/

}
