<?php

class AdminUploadsController extends BaseController {

    public static $name = 'uploads';
    public static $group = 'uploads';
    public static $entity = 'upload';
    public static $entity_name = 'файл';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	#Route::get($class::$group.'/manage', array('uses' => $class.'@getIndex'));
        	#Route::controller($class::$group, $class);

            Route::resource('uploads', $class,
                array(
                    'except' => array('show'),
                    'names' => array(
                        'index'   => 'uploads.index',
                        'create'  => 'uploads.create',
                        'store'   => 'uploads.store',
                        'edit'    => 'uploads.edit',
                        'update'  => 'uploads.update',
                        'destroy' => 'uploads.destroy',
                    )
                )
            );

        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

        $mod_tpl = static::returnTpl();
        $class = __CLASS__;

        /**
         * EXTFORM UPLOAD
         */
        ExtForm::add(
        ## Name of element
            "upload",
            ## Closure for templates (html-code)
            function($name = 'upload', $value = '', $params = null) use ($mod_tpl, $class) {
                ## default template
                $tpl = "extform_upload";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }

                #Helper::dd($value);

                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    #Helper::d($matches);
                    $val = (int)@$matches[1];
                    if ( $val > 0 ) {
                        $value = Upload::firstOrNew(array('id' => $val));
                    }
                } elseif (is_numeric($value)) {
                    $value = Upload::find($value);
                }

                #Helper::dd($params);

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'params'));
            },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #Helper::d($params);

                $file = isset($params['file']) ? $params['file'] : false;
                $upload_id = isset($params['upload_id']) ? $params['upload_id'] : false;
                $delete = isset($params['delete']) ? $params['delete'] : false;
                $return = isset($params['return']) ? $params['return'] : 'id';

                $module = isset($params['module']) ? $params['module'] : NULL;
                $unit_id = isset($params['unit_id']) ? $params['unit_id'] : NULL;

                ## Find file
                $upload = false;
                if (is_numeric($upload_id))
                    $upload = Upload::find($upload_id);

                ## Delete
                if ($delete && is_object($upload)) {
                    #Helper::dd($upload->fullpath());
                    @unlink($upload->fullpath());
                    $upload->delete();
                    if (!is_object($file))
                        return NULL;
                }

                ## If new file uploaded
                if (is_object($file)) {

                    $mimetype = $file->getMimeType();
                    $mimes = explode('/', $mimetype);

                    ## Move file
                    $dir = Config::get('site.uploads_dir', public_path('uploads/files'));
                    $file_name = time() . "_" . rand(1000, 1999) . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $file_name);
                    $path = preg_replace("~^" . addslashes(public_path()) . "~is", '', $dir . '/' . $file_name);
                    #Helper::dd(filesize($dir . '/' . $file_name));

                    $input = array(
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'filesize' => filesize($dir . '/' . $file_name),
                        'mimetype' => $mimetype,
                        'mime1' => @$mimes[0],
                        'mime2' => @$mimes[1],
                        'module' => $module,
                        'unit_id' => $unit_id,
                    );

                    ## Create new upload object if file not found
                    if (!is_object($upload)) {
                        $upload = new Upload;
                        $upload->save();
                    }
                    ## Update upload record with new path
                    $upload->update($input);

                    ## Return
                    return @$upload->$return;

                }

                ## Return exist upload_id, if no actions
                if (is_numeric($upload_id))
                    return $upload_id;
            }
        );

        /**
         * EXTFORM UPLOADS
         */
        ExtForm::add(
        ## Name of element
            "uploads",
            ## Closure for templates (html-code)
            function($name = 'uploads', $values = array(), $params = null) use ($mod_tpl, $class) {
                ## default template
                $tpl = "extform_uploads";
                ## custom template
                if (@$params['tpl']) {
                    $tpl = $params['tpl'];
                    unset($params['tpl']);
                }

                #Helper::dd($values);
                /*
                if ( $value === false || $value === null ) {
                    $val = Form::text($name);
                    preg_match("~value=['\"]([^'\"]+?)['\"]~is", $val, $matches);
                    #Helper::d($matches);
                    $val = (int)@$matches[1];
                    if ( $val > 0 ) {
                        $value = Upload::firstOrNew(array('id' => $val));
                    }
                } elseif (is_numeric($value)) {
                    $value = Upload::find($value);
                }
                */
                #Helper::dd($params);

                $params['multiple'] = 'multiple';

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'values', 'params'))->render();
            },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                $files = isset($params['files']) ? $params['files'] : false;
                #$upload_id = isset($params['upload_id']) ? $params['upload_id'] : false;

                $module = isset($params['module']) ? $params['module'] : NULL;
                $unit_id = isset($params['unit_id']) ? $params['unit_id'] : NULL;

                #Helper::dd($files);

                ## If new file uploaded
                if (isset($files) && is_array($files) && count($files)) {

                    $c = 0;
                    foreach ($files as $f => $file) {

                        if (!is_object($file))
                            continue;

                        $mimetype = $file->getMimeType();
                        $mimes = explode('/', $mimetype);

                        ## Move file
                        $dir = Config::get('site.uploads_dir', public_path('uploads/files'));
                        $file_name = time() . "_" . rand(1000, 1999) . '.' . $file->getClientOriginalExtension();
                        $file->move($dir, $file_name);
                        $path = preg_replace("~^" . addslashes(public_path()) . "~is", '', $dir . '/' . $file_name);
                        #Helper::dd(filesize($dir . '/' . $file_name));

                        $input = array(
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                            'filesize' => filesize($dir . '/' . $file_name),
                            'mimetype' => $mimetype,
                            'mime1' => @$mimes[0],
                            'mime2' => @$mimes[1],
                            'module' => $module,
                            'unit_id' => $unit_id,
                        );

                        ## Create new upload object if file not found
                        $upload = new Upload;
                        $upload->save();
                        ## Update upload record with new path
                        $upload->update($input);

                        ++$c;
                    }

                    return $c > 0;
                }

                return false;
            }
        );

    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Загрузка файлов',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return array(
            array(
                'title' => 'Загрузки',
                'link' => Helper::clearModuleLink(URL::route('uploads.index')),
                #'link' => URL::route('dic.index'),
                'class' => 'fa-download',
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/
    
	public function __construct(){

        $this->locales = Config::get('app.locales');

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl('admin'),
            'gtpl' => static::returnTpl(),

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,

            'class' => __CLASS__,
        );

        View::share('module', $this->module);
    }

    /****************************************************************************/

    public function index(){

        Allow::permission($this->module['group'], 'view');

        $elements = Upload::orderBy('created_at', 'DESC')->where('original_name', '!=', "''")->where('path', '!=', "''");

        if (!(Input::get('view') == 'all' && Allow::action($this->module['group'], 'view_all')))
            $elements = $elements->where('module', NULL)->where('unit_id', NULL);

        $elements = $elements->paginate(30);

        #Helper::tad($elements);

        return View::make($this->module['tpl'].'index', compact('elements'));
    }

    public function store() {

        Allow::permission($this->module['group'], 'create');

        #Helper::dd(Input::all());
        #Helper::d(Input::file('files'));
        #Helper::d(Input::hasFile('files.files'));
        #Helper::dd($_FILES);

        if (Input::hasFile('files.files')) {
            #Helper::d(Input::file('files'));
            $result = ExtForm::process('uploads', Input::file('files'));
            #Helper::dd($result);
        }

        return Redirect::to(URL::previous());
        #return Redirect::to(URL::route('uploads.index', $array));

    }

    public function destroy($id){

        Allow::permission($this->module['group'], 'delete');

        if(!Request::ajax())
            App::abort(404);

        $json_request = array('status' => FALSE, 'responseText' => '');

        $element = Upload::where('id', $id)->first();
        if (is_object($element)) {

            #Helper::tad($element);
            #Helper::dd($element->fullpath());

            @unlink($element->fullpath());
            $element->delete();
        }

        $json_request['responseText'] = 'Удалено';
        $json_request['status'] = TRUE;
        return Response::json($json_request,200);
    }
}


