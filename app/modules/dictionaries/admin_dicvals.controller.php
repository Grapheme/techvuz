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
            Route::get ($entity.'/{dic_slug}/{entity_id}/restore', array('as' => 'dicval.restore', 'uses' => $class.'@restore'));
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
            Route::get('/entity/{dic_slug}/{entity_id}/edit',    array('as' => 'entity.edit',    'uses' => $class.'@edit'));
            Route::put('/entity/{dic_slug}/{entity_id}/update',  array('as' => 'entity.update',  'uses' => $class.'@update'));
            Route::delete('/entity/{dic_slug}/{entity_id}',      array('as' => 'entity.destroy', 'uses' => $class.'@destroy'));

            Route::get('/entity/{dic_slug}/{entity_id}/restore', array('as' => 'entity.restore', 'uses' => $class.'@restore'));
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

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->dicval_permission($dic, 'dicval_view');

        $this->checkDicUrl($dic, $dic_id);
        $this->callHook('before_all', $dic);
        $this->callHook('before_index', $dic);

        ## Get element
        $elements = new DicVal;
        $tbl_dicval = $elements->getTable();
        $elements = $elements->where('dic_id', $dic->id)->where('version_of', '=', NULL)
            ->select($tbl_dicval . '.*')
            ->with('fields')
        ;
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

        $actions_column = false;
        if (
            Allow::action($this->module['group'], 'dicval_edit')
            || Allow::action($this->module['group'], 'dicval_delete')
            || (
                isset($dic_settings['actions']) && is_callable($dic_settings['actions'])
            )
        )
            $actions_column = true;

        $total_elements = DicVal::where('dic_id', $dic->id)->where('version_of', '=', NULL)->count();

        $this->callHook('before_index_view', $dic, $elements);

        #return View::make(Helper::acclayout());
        return View::make($this->module['tpl'].'index', compact('elements', 'dic', 'dic_id', 'sortable', 'dic_settings', 'actions_column', 'total_elements'));
	}

    /************************************************************************************/

	#public function getCreate($entity){
	public function create($dic_id){

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->dicval_permission($dic, 'dicval_create');

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

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);
        #Helper::tad($dic);

        $this->dicval_permission($dic, 'dicval_edit');

        $this->checkDicUrl($dic, $dic_id);

        $locales = $this->locales;
        $dic_settings = Config::get('dic/' . $dic->slug);

        $element = DicVal::where('id', $id)
            ->with('metas')
            #->with('meta')
            ->with('allfields')
            ->with('seos');

        if (@$dic_settings['versions'] > 0)
            $element = $element->with('versions', 'original_version.versions');

        $element = $element->first();

        $this->callHook('before_all', $dic);
        $this->callHook('before_create_edit', $dic, $element);
        $this->callHook('before_create', $dic, $element);

        if (!is_object($element))
            App::abort(404);

        $element->extract(0);
        #Helper::tad($element);

		return View::make($this->module['tpl'].'edit', compact('element', 'dic', 'dic_id', 'locales', 'dic_settings'));
	}


    /************************************************************************************/


	public function store($dic_id) {

        return $this->postSave($dic_id);
	}


	public function update($dic_id, $id) {

        return $this->postSave($dic_id, $id);
	}


	public function postSave($dic_id, $id = false){

		if(!Request::ajax())
            App::abort(404);

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->dicval_permission($dic, (@$id ? 'dicval_edit' : 'dicval_create'));

        $this->callHook('before_all', $dic);
        $this->callHook('before_store_update', $dic);

        #Helper::tad($dic);

        $versions = Config::get('dic/' . $dic->slug . '.versions');

        $input = Input::all();
        $locales = Input::get('locales');
        $fields = Helper::withdraw($input, 'fields'); #Input::get('fields');
        $fields_i18n = Input::get('fields_i18n');
        $seo = Input::get('seo');

        if (!@$input['slug'] && $dic->make_slug_from_name)
            $input['slug'] = Helper::translit($input['name']);

        #$json_request['responseText'] = "<pre>" . print_r(Input::get('seo'), 1) . "</pre>";
        $json_request['responseText'] = "<pre>" . print_r(Input::all(), 1) . "</pre>";
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

                /**
                 * Версионность
                 * Если в конфиге задано кол-во хранимых резервных копий > 0
                 */
                if ($versions > 0) {
                    $this->create_backup($element->id, true);
                }

                ## UPDATE DICVAL
                $element->update($input);
                $element->touch(); ## Важно! Для версионности

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


            /**
             * Доп. поля, не зависящие от языка
             */
            $element_fields = Config::get('dic/' . $dic->slug . '.fields');
            if (isset($element_fields) && is_callable($element_fields))
                $element_fields = $element_fields();
            #Helper::dd($element_fields);

            ## FIELDS
            if (isset($element_fields) && is_array($element_fields) && count($element_fields)) {

                #Helper::d($fields);
                foreach ($element_fields as $key => $_value) {

                    #if (is_numeric($key) || !isset($fields[$key]))
                    #    continue;

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


            /**
             * Доп. поля, имеющие версии для каждого языка
             */
            $element_fields_i18n = Config::get('dic/' . $dic->slug . '.fields_i18n');
            if (isset($element_fields_i18n) && is_callable($element_fields_i18n))
                $element_fields_i18n = $element_fields_i18n();
            #Helper::dd($element_fields_i18n);
            #Helper::dd($fields_i18n);

            ## FIELDS I18N
            #if (@is_array($fields_i18n) && count($fields_i18n)) {
            if (
                isset($element_fields_i18n) && is_array($element_fields_i18n) && count($element_fields_i18n)
                && is_array($fields_i18n) && count($fields_i18n)
            ) {

                #Helper::d('Перебираем все $element_fields_i18n...');
                #Helper::dd($fields_i18n);
                #Helper::dd($element_fields_i18n);

                foreach ($element_fields_i18n as $field_name => $field_params) {
                    #Helper::d($field_name);
                    #Helper::d($field_params);
                    #Helper::dd($fields_i18n);
                    #continue;
                    foreach ($fields_i18n as $locale_sign => $values) {

                        #Helper::d($field_name . ' => ' . @$values[$field_name]);
                        #var_dump(@$values[$field_name]);

                        #if (!isset($values[$field_name]))
                        #    continue;

                        $value = @$values[$field_name];
                        #Helper::d($field_name . ' => ' . $value);

                        ## If handler of field is defined
                        if (is_callable($handler = @$element_fields_i18n[$field_name]['handler'])) {
                            #Helper::dd($handler);
                            $value = $handler($value, $element);
                        }

                        #Helper::d($field_name . ' => ' . $value);

                        if ($value === false)
                            continue;

                        $field = DicFieldVal::firstOrNew(array('dicval_id' => $id, 'key' => $field_name, 'language' => $locale_sign));
                        $field->value = $value;
                        $field->save();
                        #Helper::ta($field);
                        unset($field);
                    }
                }
            }

            /**
             * DicValMeta
             */
            ## LOCALES
            #Helper::dd($locales);
            if (@is_array($locales) && count($locales)) {
                foreach ($locales as $locale_sign => $array) {
                    $element_meta = DicValMeta::firstOrNew(array('dicval_id' => $id, 'language' => $locale_sign));
                    $element_meta->update($array);
                    $element_meta->save();
                    Helper::tad($element_meta);
                    unset($element_meta);
                }
            }


            ## SEO
            if (@is_array($seo) && count($seo)) {
                #Helper::ta($element);
                #Helper::d($seo);
                foreach ($seo as $locale_sign => $seo_array) {
                    ## SEO
                    if (is_array($seo_array) && count($seo_array)) {
                        ###############################
                        ## Process SEO
                        ###############################
                        $seo_result = ExtForm::process('seo', array(
                            'module'  => 'DicVal',
                            'unit_id' => $element->id,
                            'data'    => $seo_array,
                            'locale'  => $locale_sign,
                        ));
                        #Helper::tad($seo_result);
                        ###############################
                    }
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

		if(!Request::ajax())
            App::abort(404);

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        if (!$this->checkDicPermission($dic))
            App::abort(404);

        $this->dicval_permission($dic, 'dicval_delete');

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


    /**
     * Функция сохраняет текущее состояние записи,
     * восстанавливает состояние записи из резервной копии
     * и удаляет все резервные копии, превысившие лимит
     *
     * @param $dic_id
     * @param $id
     * @return string
     * @throws Exception
     */
    public function restore($dic_id, $id) {

        $dic = Dictionary::where(is_numeric($dic_id) ? 'id' : 'slug', $dic_id)->first();
        $versions = Config::get('dic/' . $dic->slug . '.versions');

        if (!$this->checkDicPermission($dic) || !@$versions)
            App::abort(404);

        $this->dicval_permission($dic, 'dicval_restore');

        /**
         * Находим запись резервной копии для восстановления
         */
        $version = DicVal::where('id', $id)
            ->with('allfields', 'metas', 'seo')
            ->first();

        if (!isset($version) || !is_object($version) || $version->version_of == NULL)
            return Redirect::to(URL::previous());

        /**
         * Находим запись оригинала
         */
        $element = DicVal::where('id', $version->version_of)
            ->with('allfields', 'metas', 'seo', 'versions')
            ->first();

        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return Redirect::to(URL::previous());

        #Helper::ta($version);
        #Helper::ta($element);
        #dd();

        #Helper::ta($element->versions);

        /**
         * Создаем резервную копию оригинальной записи
         */
        $create_backup_result = $this->create_backup($version->version_of, false);

        if (!$create_backup_result) {
            throw new Exception("Can't create backup of original record");
        }

        #Helper::tad($element->versions);

        /**
         * Восстанавливаем содержимое записи из резервной копии
         */
        $restore_backup_result = $this->restore_backup($version->id);

        if (!$restore_backup_result) {
            throw new Exception("Can't restore backup of original record");
        }

        /**
         * Удаляем старые резервные копии (если их больше лимита)
         */
        $delete_backup_result = $this->delete_backups($element->id);

        if (!$delete_backup_result) {
            throw new Exception("Can't delete over backups of original record");
        }

        #Helper::dd((int)$create_backup_result . ' / ' . (int)$restore_backup_result . ' / ' . (int)$delete_backup_result);

        $url = action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $element->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '');

        #Helper::d($element);
        #Helper::dd($url);

        #return Redirect::to($url);
        Redirect($url);
        return '';
    }


    /**
     * Функция создания бэкапа из текущей версии записи, с возможностью удаления превысивших лимит резервных копий
     *
     * @param int $dicval_id
     * @param bool $delete_over_backups
     * @return bool
     */
    private function create_backup($dicval_id = 0, $delete_over_backups = true) {

        /**
         * Находим запись словаря для создания ее бэкапа
         * Запись должна быть оригиналом, т.е. иметь version_of = NULL
         */
        $element = DicVal::where('id', $dicval_id)
            ->with('dic', 'metas', 'allfields', 'seos', 'versions')
            ->first();
        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return false;

        #Helper::tad($element);

        $dic = $element->dic;
        $versions = Config::get('dic/' . $dic->slug . '.versions');
        $element_versions = $element->versions;

        /**
         * Создадим резервную копию записи
         */
        $version_array = $element->toArray();
        unset($version_array['versions'], $version_array['id'], $version_array['created_at'], $version_array['updated_at']);
        $version_array['version_of'] = $element->id;
        $new_version = DicVal::create($version_array);
        #Helper::d($version_array);
        #Helper::tad($new_version);

        /**
         * Создадим резервные копии всех доп. полей текущей записи,
         * привязав их к только что созданной резервной копии записи
         */
        $element_allfields = $element->allfields;
        #Helper::tad($element_allfields);
        if (count($element_allfields)) {
            foreach ($element_allfields as $a => $allfield) {
                $allfield = $allfield->toArray();
                #Helper::dd($allfield);
                unset($allfield['id'], $allfield['created_at'], $allfield['updated_at']);
                $allfield['dicval_id'] = $new_version->id;
                $temp = DicFieldVal::firstOrCreate($allfield);
            }
        }

        /**
         * Создадим резервные копии всех META данных текущей записи
         */
        $element_metas = $element->metas;
        #Helper::tad($element_metas);
        if (count($element_metas)) {
            foreach ($element_metas as $e => $element_meta) {
                if (isset($element_meta) && is_object($element_meta) && $element_meta->id) {
                    #Helper::ta($element_meta);
                    $meta_backup = $element_meta->toArray();
                    unset($meta_backup['id'], $meta_backup['created_at'], $meta_backup['updated_at']);
                    $meta_backup['dicval_id'] = $new_version->id;
                    $temp = DicValMeta::firstOrCreate($meta_backup);
                }
            }
        }

        /**
         * Создадим резервные копии SEO данных текущей записи
         */
        $element_seos = $element->seos;
        #Helper::tad($element_seos);
        if (count($element_seos)) {
            foreach ($element_seos as $e => $element_seo) {
                #$seo_data = Seo::where('module', 'DicVal')->where('unit_id', $element->id)->where('language', $locale_sign)->first();
                if (isset($element_seo) && is_object($element_seo) && $element_seo->id) {
                    $seo_backup = $element_seo->toArray();
                    unset($seo_backup['id'], $seo_backup['created_at'], $seo_backup['updated_at']);
                    $seo_backup['unit_id'] = $new_version->id;
                    #Helper::ta($seo_backup);
                    if (class_exists('Seo'))
                        $temp = Seo::firstOrCreate($seo_backup);
                }
            }
        }

        /**
         * Если кол-во существующих версий > заданного в конфиге лимита - удалим все старые версии, оставив (LIMIT-1) самых свежих
         * В данный момент count($element_versions) уже реально на 1 больше, т.к. мы только что создали свежую резервную копию!
         */
        #Helper::dd((int)$delete_over_backups);
        if (count($element_versions) >= $versions && $delete_over_backups) {
            $this->delete_backups($element->id);
        }

        return true;
    }


    /**
     * Функция восстанавливает состояние записи из резервной копии
     *
     * @param int $dicval_id
     * @return bool
     */
    private function restore_backup($dicval_id = 0) {

        /**
         * Находим резервную копию записи словаря для восстановления
         * Она должна быть резервной копией, т.е. иметь version_of != NULL
         */
        $version = DicVal::where('id', $dicval_id)
            ->with('dic', 'allfields', 'metas', 'seos')
            ->first();
        if (!isset($version) || !is_object($version)) {
            return false;
        }
        #Helper::tad($version);

        /**
         * Находим запись оригинала
         */
        $element = DicVal::where('id', $version->version_of)
            ->with('allfields', 'metas', 'seos')
            ->first();
        if (!isset($element) || !is_object($element)) {
            return false;
        }

        /**
         * Временно меняем id оригинала. Если восстановление не удастся - придется его восстановить
         */
        $original_id = $element->id;
        #$element->id = 0;

        /**
         * Открываем транзакцию
         */
        DB::transaction(function () use ($element, $version, $original_id) {

            /**
             * Удаляем все доп. поля оригинала, и ставим на их место доп. поля резервной копии
             */
            if (count($element->allfields)) {
                foreach ($element->allfields as $e => $allfield) {
                    $allfield->delete();
                }
                foreach ($version->allfields as $v => $allfield) {
                    $allfield->dicval_id = $original_id;
                    $allfield->save();
                }
            }

            /**
             * Удаляем все META данные оригинала, и ставим на их место META данные резервной копии
             */
            if (count($element->metas)) {
                foreach ($element->metas as $e => $meta) {
                    $meta->delete();
                }
                foreach ($version->metas as $v => $meta) {
                    $meta->dicval_id = $original_id;
                    $meta->save();
                }
            }

            /**
             * Удаляем все SEO данные оригинала, и ставим на их место SEO данные резервной копии
             */
            if (count($element->seos) && class_exists('Seo')) {
                foreach ($element->seos as $e => $seo) {
                    $seo->delete();
                }
                foreach ($version->seos as $v => $seo) {
                    $seo->unit_id = $original_id;
                    $seo->save();
                }
            }

            /**
             * Удаляем оригинал
             */
            $element->delete();

            /**
             * Ставим бекап на место оригинала
             */
            $version->id = $original_id;
            $version->version_of = NULL;
            $version->save();
        });

        /**
         * Проверяем, успешно ли выполнились все запросы внутри транзакции, и возвращаем результат
         */
        return ($version->id == $original_id);
    }


    /**
     * Функция удаляет резервные копии, превысившие лимит
     *
     * @param int $dicval_id
     * @return bool
     */
    private function delete_backups($dicval_id = 0) {

        /**
         * Находим запись словаря для удаления ее бэкапов
         * Запись должна быть оригиналом, т.е. иметь version_of = NULL
         */
        $element = DicVal::where('id', $dicval_id)->with('dic', 'metas', 'allfields', 'seos', 'versions')->first();
        if (!isset($element) || !is_object($element) || $element->version_of != NULL)
            return false;

        #Helper::tad($element);

        $dic = $element->dic;
        $versions = Config::get('dic/' . $dic->slug . '.versions');
        $element_versions = $element->versions;

        $result = true;

        if (count($element_versions) > 0 && count($element_versions) >= $versions) {
            /**
             * Вычисляем ID записей, подлежащих удалению
             */
            $for_delete = $element_versions->lists('id');
            krsort($for_delete);
            $for_delete = array_slice($for_delete, 0, count($element_versions)-$versions);
            #Helper::dd($for_delete);

            if (count($for_delete)) {
                $result = false;
                /**
                 * Открываем транзакцию
                 */
                DB::transaction(function () use ($element, $for_delete, $result) {
                    /**
                     * Удаляем старые резервные копии и их доп. поля, META и SEO-данные
                     */
                    DicFieldVal::whereIn('dicval_id', $for_delete)->delete();
                    DicValMeta::whereIn('dicval_id', $for_delete)->delete();
                    if (Allow::module('seo')) {
                        Seo::where('module', 'DicVal')->whereIn('unit_id', $for_delete)->delete();
                    }
                    $deleted = DicVal::where('version_of', $element->id)->whereIn('id', $for_delete)->delete();
                    #Helper::d($deleted);
                    #Helper::dd($for_delete);
                    if ($deleted)
                        $result = true;
                });
            }
        }

        return $result;
    }


    private function checkDicPermission($dic) {

        if (!is_object($dic))
            return false;

        $return = true;

        if ((int)$dic->view_access == 0)
            $return = true;
        elseif ((int)$dic->view_access == 1 && !Allow::superuser())
            $return = false;
        elseif ((int)$dic->view_access == 2 && !Allow::action($this->module['group'], 'hidden'))
            $return = false;

        #Helper::dd($return);

        return $return;
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

    private function dicval_permission($dic, $permission = '') {

        /**
         * Устанавливаем права доступа текущего пользователя к словарю, из конфига (если они заданы)
         */
        $dic_settings = Config::get('dic/' . $dic->slug);
        if (@is_object(Auth::user()) && @is_object(Auth::user()->group) && NULL != ($user_group_name = Auth::user()->group->name)) {
            #Helper::dd($user_group_name);
            #$user_group_name = 'moderator';
            $dicval_permissions = @$dic_settings['group_actions'][$user_group_name];
            #Helper::dd($dicval_permissions);
            if (isset($dicval_permissions) && @is_callable($dicval_permissions)) {
                $dicval_permissions = $dicval_permissions();
                #Helper::dd($dicval_permissions);
                Allow::set_actions($this->module['group'], $dicval_permissions);
            }
        }

        /**
         * Проверяем, есть ли у пользователя необходимые права для выполнения действия
         */
        Allow::permission($this->module['group'], $permission);
    }

}


