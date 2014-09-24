<?php

class AdminDicvalsController extends BaseController {

    public static $name = 'dicvalues';
    public static $group = 'dictionaries';
    public static $entity = 'dicval';
    public static $entity_name = 'запись словаря';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        $entity = self::$entity;

        Route::group(array('before' => 'auth', 'prefix' => $prefix . "/" . $class::$group), function() use ($class, $entity) {
            Route::post($entity.'/ajax-order-save', array('as' => 'dicval.order', 'uses' => $class."@postAjaxOrderSave"));
            Route::resource('dic.val', $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => 'dicval.index',
                        'create'  => 'dicval.create',
                        'store'   => 'dicval.store',
                        'edit'    => 'dicval.edit',
                        'update'  => 'dicval.update',
                        'destroy' => 'dicval.destroy',
                    )
                )
            );
        });

        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class, $entity) {
            Route::resource('/entity/{dic_slug}/', $class,
                array(
                    'except' => array('show', 'edit', 'update', 'destroy'),
                    'names' => array(
                        'index'   => 'entity.index',
                        'create'  => 'entity.create',
                        'store'   => 'entity.store',
                        #'edit'    => 'entity.edit',
                        #'update'  => 'entity.update',
                        #'destroy' => 'entity.destroy',
                    )
                )
            );
            Route::get('/entity/{dic_slug}/{entity_id}/edit',   array('as' => 'entity.edit',    'uses' => $class.'@edit'));
            Route::put('/entity/{dic_slug}/{entity_id}/update', array('as' => 'entity.update',  'uses' => $class.'@update'));
            Route::delete('/entity/{dic_slug}/{entity_id}',     array('as' => 'entity.destroy', 'uses' => $class.'@destroy'));
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
        ##
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        ##
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        ##
    }
        
    /****************************************************************************/
    
	public function __construct(){

        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin/dicvals'),
            'gtpl' => static::returnTpl(),

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );

        View::share('module', $this->module);
        View::share('CLASS', __CLASS__);
	}

	#public function getIndex(){
	public function index($dic_id){

        Allow::permission($this->module['group'], 'dicval_view');

        #Helper::dd($dic_id);

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->checkDicUrl($dic, $dic_id);
        $this->callHook('before_all', $dic);
        $this->callHook('before_index', $dic);

        ## Get element
        $elements = new DicVal;
        $tbl_dicval = $elements->getTable();
        $elements = $elements->where('dic_id', $dic->id)->select($tbl_dicval . '.*')->with('fields');
        #$elements = DB::table('dictionary_values')->where('dic_id', $dic->id)->select('dictionary_values.*');

        if (NULL !== ($filter = Input::get('filter'))) {

            #Helper::d($filter);
            if (isset($filter['fields']) && is_array($filter) && count($filter)) {

                $tbl_fields = new DicFieldVal();
                $tbl_fields = $tbl_fields->getTable();

                #Helper::d($filter['fields']);
                foreach ($filter['fields'] as $key => $value) {
                    $rand_tbl_alias = md5(rand(99999, 999999));
                    $elements = $elements
                        ->join($tbl_fields . ' AS ' . $rand_tbl_alias, function ($join) use ($tbl_dicval, $tbl_fields, $key, $value, $rand_tbl_alias) {
                            $join
                                ->on($rand_tbl_alias . '.dicval_id', '=', $tbl_dicval . '.id')
                                ->where($rand_tbl_alias . '.key', '=', $key)
                                ->where($rand_tbl_alias . '.value', '=', $value)
                            ;
                        })
                        ->addSelect($rand_tbl_alias . '.value AS ' . $key)
                    ;
                }
            }
        }

        ## Ordering
        $sort_order = $dic->sort_order_reverse ? 'DESC' : 'ASC';
        switch ($dic->sort_by) {
            case '':
                $elements = $elements->orderBy('order', $sort_order)->orderBy('name', $sort_order);
                break;
            case 'name':
                $elements = $elements->orderBy('name', $sort_order);
                break;
            case 'slug':
                $elements = $elements->orderBy('slug', $sort_order);
                break;
            case 'created_at':
                $elements = $elements->orderBy('created_at', $sort_order);
                break;
            case 'updated_at':
                $elements = $elements->orderBy('updated_at', $sort_order);
                break;
        }

        ## Pagination
        if ($dic->pagination > 0)
            $elements = $elements->paginate($dic->pagination);
        else
            $elements = $elements->get();

        $sortable = ($dic->sortable && $dic->pagination == 0 && $dic->sort_by == NULL) ? true : false;

        DicVal::extracts($elements, true);
        #Helper::tad($elements);

        $dic_settings = Config::get('dic/' . $dic->slug);
        #Helper::dd($dic_settings);

        $this->callHook('before_index_view', $dic, $elements);

        #return View::make(Helper::acclayout());
        return View::make($this->module['tpl'].'index', compact('elements', 'dic', 'dic_id', 'sortable', 'dic_settings'));
	}

    /************************************************************************************/

	#public function getCreate($entity){
	public function create($dic_id){

        Allow::permission($this->module['group'], 'dicval_create');

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->checkDicUrl($dic, $dic_id);
        $this->callHook('before_all', $dic);
        $this->callHook('before_create_edit', $dic);
        $this->callHook('before_create', $dic);

        $locales = $this->locales;
        $dic_settings = Config::get('dic/' . $dic->slug);

        $element = new Dictionary;

        return View::make($this->module['tpl'].'edit', compact('element', 'dic', 'dic_id', 'locales', 'dic_settings'))->render();
	}
    

	#public function getEdit($entity, $id){
	public function edit($dic_id, $id){

        Allow::permission($this->module['group'], 'dicval_edit');

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);
        #Helper::tad($dic);

        $this->checkDicUrl($dic, $dic_id);

        $locales = $this->locales;
        $dic_settings = Config::get('dic/' . $dic->slug);

        $element = DicVal::where('id', $id)
            ->with('metas')
            #->with('meta')
            ->with('allfields')
            ->first()
            #->extract(1)
        ;

        $this->callHook('before_all', $dic);
        $this->callHook('before_create_edit', $dic, $element);
        $this->callHook('before_create', $dic, $element);

        #Helper::tad($element);

		return View::make($this->module['tpl'].'edit', compact('element', 'dic', 'dic_id', 'locales', 'dic_settings'));
	}


    /************************************************************************************/


	public function store($dic_id) {

        Allow::permission($this->module['group'], 'dicval_create');
        return $this->postSave($dic_id);
	}


	public function update($dic_id, $id) {

        Allow::permission($this->module['group'], 'dicval_edit');
        return $this->postSave($dic_id, $id);
	}


	public function postSave($dic_id, $id = false){

        if (@$id)
            Allow::permission($this->module['group'], 'dicval_edit');
        else
            Allow::permission($this->module['group'], 'dicval_create');

		if(!Request::ajax())
            App::abort(404);

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->callHook('before_all', $dic);
        $this->callHook('before_store_update', $dic);

        #Helper::tad($dic);

        $input = Input::all();
        $locales = Input::get('locales');
        $fields = Helper::withdraw($input, 'fields'); #Input::get('fields');
        $fields_i18n = Input::get('fields_i18n');

        if (!@$input['slug'] && $dic->make_slug_from_name)
            $input['slug'] = Helper::translit($input['name']);

        $json_request['responseText'] = "<pre>" . print_r($_POST, 1) . "</pre>";
        #return Response::json($json_request,200);

        $json_request = array('status' => FALSE, 'responseText' => '', 'responseErrorText' => '', 'redirect' => FALSE);
		$validator = Validator::make($input, array());
		if($validator->passes()) {

            $redirect = false;

            #Helper::d($id);

            $mode = '';

            if ($id > 0 && NULL !== ($element = DicVal::find($id))) {

                $mode = 'update';

                $this->callHook('before_update', $dic, $element);

                ## UPDATE DICVAL
                $element->update($input);

            } else {

                $mode = 'store';

                $this->callHook('before_store', $dic);

                if (@!$input['dic_id'])
                    $input['dic_id'] = $dic->id;

                #$json_request['responseText'] = "<pre>" . print_r($input, 1) . "</pre>";
                #return Response::json($json_request,200);

                ## CREATE DICVAL
                $element = new DicVal;
                #$element = DicVal::insert($input);
                $element->save();
                $element->update($input);
                $id = $element->id;
                $redirect = true;
            }

            $element_fields = Config::get('dic/' . $dic->slug . '.fields');
            if (isset($element_fields) && is_callable($element_fields))
                $element_fields = $element_fields();
            #Helper::dd($element_fields);

            ## FIELDS
            if (isset($element_fields) && is_array($element_fields) && count($element_fields)) {

                #Helper::d($fields);
                foreach ($element_fields as $key => $_value) {

                    if (is_numeric($key))
                        continue;

                    #Helper::d($key);

                    $value = @$fields[$key];

                    #Helper::d($value);
                    #continue;

                    ## If handler of field is defined
                    if (is_callable($handler = @$element_fields[$key]['handler'])) {
                        #Helper::d($handler);
                        $value = $handler($value, $element);
                    }

                    #Helper::d($key . ' => ' . $value);

                    if ($value === false)
                        continue;

                    $field = DicFieldVal::firstOrNew(array('dicval_id' => $id, 'key' => $key, 'language' => NULL));
                    $field->value = $value;
                    $field->save();
                    unset($field);
                }
            }

            $element_fields_i18n = Config::get('dic/' . $dic->slug . '.fields_i18n');
            if (isset($element_fields_i18n) && is_callable($element_fields_i18n))
                $element_fields_i18n = $element_fields_i18n();
            #Helper::dd($element_fields_i18n);

            ## FIELDS I18N
            #if (@is_array($fields_i18n) && count($fields_i18n)) {
            if (isset($element_fields_i18n) && is_array($element_fields_i18n) && count($element_fields_i18n)) {

                #Helper::d($fields_i18n);

                #foreach ($fields_i18n as $locale_sign => $locale_values) {
                foreach ($element_fields_i18n as $locale_sign => $locale_values) {
                    #Helper::d($locale_values);
                    foreach ($locale_values as $key => $_value) {

                        if (is_numeric($key))
                            continue;

                        ##
                        ## Need to testing!!!
                        ##
                        $value = @$fields_i18n[$locale_sign][$key];
                        #Helper::d($value);

                        ## If handler of field is defined
                        if (is_callable($handler = @$element_fields[$key]['handler'])) {
                            #Helper::dd($handler);
                            $value = $handler($value);
                        }
                        #Helper::d($value);

                        if ($value === false)
                            continue;

                        $field = DicFieldVal::firstOrNew(array('dicval_id' => $id, 'key' => $key, 'language' => $locale_sign));
                        $field->value = $value;
                        $field->save();
                        #Helper::ta($field);
                        unset($field);
                    }
                }
            }

            ## LOCALES
            if (@is_array($locales) && count($locales)) {
                #Helper::dd($locales);
                foreach ($locales as $locale_sign => $array) {

                    $element_meta = DicValMeta::firstOrNew(array('dicval_id' => $id, 'language' => $locale_sign));
                    $element_meta->update($array);
                    $element_meta->save();
                    unset($element_meta);
                }
            }

            if ($mode == 'update')
                $this->callHook('after_update', $dic, $element);
            elseif ($mode == 'store')
                $this->callHook('after_store', $dic, $element);

			$json_request['responseText'] = 'Сохранено';
            if ($redirect && Input::get('redirect'))
			    $json_request['redirect'] = Input::get('redirect');
			$json_request['status'] = TRUE;
		} else {
			$json_request['responseText'] = 'Неверно заполнены поля';
			$json_request['responseErrorText'] = $validator->messages()->all();
		}
		return Response::json($json_request, 200);
	}

    /************************************************************************************/

	#public function deleteDestroy($entity, $id){
	public function destroy($dic_id, $id){

        Allow::permission($this->module['group'], 'dicval_delete');

		if(!Request::ajax())
            App::abort(404);

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '');

        $element = DicVal::where('id', $id)->with('allfields', 'metas')->first();

        $this->callHook('before_all', $dic);
        $this->callHook('before_destroy', $dic, $element);

        if (is_object($element)) {

            #Helper::tad($element);

            if (@count($element->allfields))
                foreach ($element->allfields as $el)
                    $el->delete();

            if (@count($element->metas))
                foreach ($element->metas as $el)
                    $el->delete();

            $element->delete();
        }

        $this->callHook('after_destroy', $dic, $element);

        $json_request['responseText'] = 'Удалено';
		$json_request['status'] = TRUE;
		return Response::json($json_request,200);
	}


    public function postAjaxOrderSave() {

        $poss = Input::get('poss');

        $pls = DicVal::whereIn('id', $poss)->get();

        if ( $pls ) {
            foreach ( $pls as $pl ) {
                $pl->order = array_search($pl->id, $poss);
                $pl->save();
            }
        }

        return Response::make('1');
    }

    public function is_available($dic) {
        return self::checkDicPermission($dic);
    }

    private function checkDicPermission($dic) {

        if (!is_object($dic))
            return false;

        $return = true;

        if ((int)$dic->view_access === 0)
            $return = true;
        elseif ((int)$dic->view_access === 1 && !Allow::superuser())
            $return = false;
        elseif ((int)$dic->view_access === 2 && !Allow::action($this->module['group'], 'hidden'))
            $return = false;

        #Helper::dd($return);

        return $return;
    }

    private function callHook($hook_name = '', $dic = false, $dicval = false) {
        if (!$hook_name)
            return false;
        $hook = Config::get('dic/' . $dic->slug . '.hooks.' . $hook_name);
        if (@$hook && @is_callable($hook))
            $hook($dic, $dicval);

    }

    private function checkDicUrl($dic, $dic_id) {
        $qs = Request::getQueryString();
        if ($qs != '')
            $qs = '?' . $qs;
        if ($dic->entity && is_numeric($dic_id))
            Redirect(URL::route('entity.index', $dic->slug).$qs);
        elseif (!$dic->entity && !is_numeric($dic_id))
            Redirect(URL::route('dicval.index', $dic->id).$qs);
    }

}


