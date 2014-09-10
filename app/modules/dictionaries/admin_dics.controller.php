<?php

class AdminDicsController extends BaseController {

    public static $name = 'dics';
    public static $group = 'dictionaries';
    public static $entity = 'dic';
    public static $entity_name = 'словарь';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        $entity = self::$entity;

        Route::group(array('before' => 'auth', 'prefix' => $prefix . "/" . $class::$group), function() use ($class, $entity) {
            Route::post($entity.'/ajax-order-save', array('as' => 'dic.order', 'uses' => $class."@postAjaxOrderSave"));
            Route::resource('dic', $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => 'dic.index',
                        'create'  => 'dic.create',
                        'store'   => 'dic.store',
                        'edit'    => 'dic.edit',
                        'update'  => 'dic.update',
                        'destroy' => 'dic.destroy',
                    )
                )
            );
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
        ##
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        ##return array();
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        ##
    }
        
    /****************************************************************************/
    
	public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin/dics'),
            'gtpl' => static::returnTpl(),

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,

            'class' => __CLASS__,
        );

        View::share('module', $this->module);
	}

	#public function getIndex(){

	public function index(){

        Allow::permission($this->module['group'], 'view');

        $elements = Dictionary::orderBy('name', 'ASC');

        if (!Allow::superuser() || Allow::permission($this->module['group'], 'edit'))
            $elements = $elements->where('entity', NULL);

        $elements = $elements->paginate(30);

        #Helper::dd($elements);

        return View::make($this->module['tpl'].'index', compact('elements'));
	}

    /************************************************************************************/

	#public function getCreate($entity){
	public function create(){

        Allow::permission($this->module['group'], 'create');

        $element = new Dictionary;
		return View::make($this->module['tpl'].'edit', compact('element'));
	}
    

	#public function getEdit($entity, $id){
	public function edit($id){

        Allow::permission($this->module['group'], 'edit');

		$element = Dictionary::find($id);
		return View::make($this->module['tpl'].'edit', compact('element'));
	}


    /************************************************************************************/


	public function store() {

        Allow::permission($this->module['group'], 'create');

		return $this->postSave();
	}


	public function update($id) {

        Allow::permission($this->module['group'], 'edit');

		return $this->postSave($id);
	}


	public function postSave($id = false){

        #Helper::dd($entity);

        if (@$id)
            Allow::permission($this->module['group'], 'edit');
        else
            Allow::permission($this->module['group'], 'create');

		if(!Request::ajax())
            return App::abort(404);

        #$id = Input::get('id');
                
        #$input = Input::all();
        $input = array(
            'slug' => Input::get('slug'),
            'name' => Input::get('name'),
            'entity' => Input::get('entity') ? 1 : NULL,
            'icon_class' => Input::get('icon_class'),
            'hide_slug' => Input::get('hide_slug') ? 1 : NULL,
            'name_title' => Input::get('name_title') ?: NULL,
        );

        $json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
        #return Response::json($json_request,200);

        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		$validator = Validator::make($input, array('name' => 'required'));
		if($validator->passes()) {

            $redirect = false;

            if ($id > 0) {

                if (NULL !== Dictionary::find($id)) {
    
        		    #$json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
        		    #return Response::json($json_request,200);

                    Dictionary::find($id)->update($input);
                    $redirect = false;
                }

            } else {

                $dic_id = Dictionary::insertGetId($input);
                $redirect = action('dicval.index', array('dic_id' => $dic_id));
            }

			$json_request['responseText'] = 'Сохранено';
            if ($redirect && $redirect)
			    $json_request['redirect'] = $redirect;
			$json_request['status'] = TRUE;
		} else {
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = $validator->messages()->all();
		}
		return Response::json($json_request, 200);
	}

    /************************************************************************************/

	#public function deleteDestroy($entity, $id){
	public function destroy($id){

        Allow::permission($this->module['group'], 'delete');

		if(!Request::ajax())
            return App::abort(404);

		$json_request = array('status'=>FALSE, 'responseText'=>'');

        if (NULL !== Dictionary::find($id))
            Dictionary::find($id)->delete();

		$json_request['responseText'] = 'Удалено';
		$json_request['status'] = TRUE;
		return Response::json($json_request,200);
	}

    public function postAjaxOrderSave() {

        $poss = Input::get('poss');

        $pls = Dic::whereIn('id', $poss)->get();

        if ( $pls ) {
            foreach ( $pls as $pl ) {
                $pl->order = array_search($pl->id, $poss);
                $pl->save();
            }
        }

        return Response::make('1');
    }
}


