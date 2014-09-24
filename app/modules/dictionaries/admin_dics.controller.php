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

            Route::get('dic/{dic_id}/import',  array('as' => 'dic.import',   'uses' => $class.'@getImport'));
            Route::post('dic/{dic_id}/import2', array('as' => 'dic.import2', 'uses' => $class.'@postImport2'));
            Route::post('dic/{dic_id}/import3', array('as' => 'dic.import3', 'uses' => $class.'@postImport3'));

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

        $elements = new Dictionary;
        $tbl_dic = $elements->getTable();

        ## Ordering
        $elements = $elements->orderBy('order', 'ASC')->orderBy('name', 'ASC');

        ## View access
        if (!Allow::superuser())
            $elements = $elements->where('view_access', '!=',  '1');
        if (!Allow::action($this->module['group'], 'hidden'))
            $elements = $elements->where('view_access', '!=',  '2');

        #Helper::d(Allow::superuser());
        #Helper::d('-');
        #Helper::dd(Allow::action($this->module['group'], 'edit'));

        ## Hide dics, which are entities
        if (!Allow::superuser() && !Allow::action($this->module['group'], 'hidden'))
            $elements = $elements->where('entity', NULL);

        $tbl_dicval = new DicVal;
        $tbl_dicval = $tbl_dicval->getTable();

        #$elements = $elements->paginate(30);
        $elements = $elements
            ->select($tbl_dic.'.*', DB::raw('COUNT(`' . $tbl_dicval . '`.`id`) AS count'))
            ->leftJoin($tbl_dicval, $tbl_dicval.'.dic_id', '=', $tbl_dic.'.id')
            ->groupBy($tbl_dic.'.id')
            ->get();

        #Helper::tad($elements);

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
            App::abort(404);

        #$id = Input::get('id');
                
        $input = Input::all();

        if (Allow::action($this->module['group'], 'settings')) {
            $input['entity'] = Input::get('entity') ? 1 : NULL;
            $input['hide_slug'] = Input::get('hide_slug') ? 1 : NULL;
            $input['make_slug_from_name'] = Input::get('make_slug_from_name') ? 1 : NULL;
            $input['name_title'] = Input::get('name_title') ?: NULL;
            $input['view_access'] = Input::get('view_access') ?: NULL;
            $input['sortable'] = Input::get('sortable') ? 1 : 0;
            $input['sort_by'] = Input::get('sort_by') != 'order' ? Input::get('sort_by') : NULL;
        }

        $json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
        #return Response::json($json_request,200);

        $json_request = array('status'=>FALSE, 'responseText'=>'', 'responseErrorText'=>'', 'redirect'=>FALSE);
		$validator = Validator::make($input, array('name' => 'required'));
		if($validator->passes()) {

            $redirect = false;

            if ($id > 0 && NULL !== ($element = Dictionary::find($id))) {

                #$json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
                #return Response::json($json_request,200);

                $element->update($input);
                $redirect = false;

            } else {

                $element = new Dictionary;
                $element->save();
                $element->update($input);
                $dic_id = $element->id;
                $redirect = action('dicval.index', array('dic_id' => $dic_id));
            }

			$json_request['responseText'] = 'Сохранено';
            if ($redirect)
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
            App::abort(404);

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

    public function getImport($dic_id){

        Allow::permission($this->module['group'], 'import');

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!is_object($dic))
            App::abort(404);

        #Helper::dd($dic);

        $element = $dic;

        return View::make($this->module['tpl'].'import', compact('dic', 'dic_id', 'element'));
    }

    public function postImport2($dic_id){

        Allow::permission($this->module['group'], 'import');

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!is_object($dic))
            App::abort(404);
        #Helper::tad($dic);

        #Helper::dd( Input::all() );

        $input = Input::all();
        $lines = explode("\n", $input['import_data']);
        $array = array();
        $max = 0;
        foreach ($lines as $line) {
            if (@$input['trim'])
                $line = trim($line, $input['trim_params'] . ' ' ?: ' ');
            if (@$input['delimeter'])
                $line = explode($input['delimeter'], $line);
            else
                $line = array($line);

            if (count($line) > $max)
                $max = count($line);

            if ($line)
                $array[] = $line;
        }

        #Helper::dd($array);

        $fields = array('Выберите...', 'name' => 'Название', 'slug' => 'Системное имя') + array_keys((array)Config::get('dic.fields.' . $dic->slug));
        #Helper::dd($fields);

        $element = $dic;

        return View::make($this->module['tpl'].'import2', compact('dic', 'dic_id', 'element', 'array', 'max', 'fields'));
    }

    public function postImport3($dic_id){

        Allow::permission($this->module['group'], 'import');

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)
            #->with('values')
            ->first();

        if (!is_object($dic))
            App::abort(404);
        #Helper::tad($dic);

        ## Get also exists values
        #$exist_values = $dic->values;
        #Helper::ta($exist_values);

        $input = Input::all();

        /*
        foreach ($exist_values as $e => $exist_value) {
            if ($input['rewrite_mode'] == 1)
                $exist_values[$exist_value->name] = $exist_value;
            else
                $exist_values[$exist_value->slug] = $exist_value;
            unset($exist_values[$e]);
        }
        Helper::ta($exist_values);
        */

        $max = count($input['values'][0]);

        $fields = $input['fields'];
        $values = $input['values'];

        ## Filter fields & values
        foreach ($fields as $f => $field) {
            if (is_numeric($field) && $field == 0) {
                #Helper::d($f . " => " . $field . " = 0");
                unset($fields[$f]);
                unset($values[$f]);
            }
        }

        #Helper::d($fields);
        #Helper::d($values);

        ## Make insertions
        $find_key = ($input['rewrite_mode'] == 1) ? 'name' : 'slug';
        $array = array();
        $count = count($values[0]);
        for ($i = 0; $i < $count; $i++) {
            $arr = array(
                'dic_id' => $dic->id,
            );
            foreach ($fields as $f => $field) {
                $arr[$field] = @trim($values[$f][$i]);
            }

            $find = array($find_key => @$arr[$find_key], 'dic_id' => $dic->id);
            #unset($arr[$find_key]);
            if (
                #$find_key != 'slug'
                @$input['set_slug']
                && (
                    $input['set_slug_elements'] == 'all'
                    || ($input['set_slug_elements'] == 'empty' && !@$arr['slug'])
                )
            ) {
                $arr['slug'] = Helper::translit(@$arr['name']);
            }

            if (@$input['set_ucfirst'] && $arr['name']) {
                $arr['name'] = Helper::mb_ucfirst($arr['name']);
            }

            #Helper::dd($find);

            #/*
            $dicval = DicVal::firstOrCreate($find);
            $dicval->update($arr);
            #Helper::ta($dicval);
            #*/

            unset($dicval);
            #$array[] = $arr;
        }

        #Helper::d($array);

        return Redirect::route('dicval.index', $dic_id);
    }

}


