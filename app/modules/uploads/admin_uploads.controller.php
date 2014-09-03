<?php

class AdminUploadsController extends BaseController {

    public static $name = 'uploads';
    public static $group = 'uploads';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	#Route::get($class::$group.'/manage', array('uses' => $class.'@getIndex'));
        	#Route::controller($class::$group, $class);
        });
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Extended Form elements of module
    public static function returnExtFormElements() {

        $mod_tpl = static::returnTpl();
        $class = __CLASS__;

        ##
        ## EXTFORM ELEMENTS
        ##
        /*
        ################################################
        ## Process upload
        ################################################
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

                ## return view with form element
                return View::make($mod_tpl.$tpl, compact('name', 'value', 'params'));
    	    },
            ## Processing results closure
            function($params) use ($mod_tpl, $class) {

                #Helper::dd($params);

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

                    ## Move file
                    $dir = Config::get('site.uploads_dir', public_path('uploads/files'));
                    $file_name = time() . "_" . rand(1000, 1999) . '.' . $file->getClientOriginalExtension();
                    $file->move($dir, $file_name);
                    $path = preg_replace("~^" . addslashes(public_path()) . "~is", '', $dir . '/' . $file_name);

                    ## Create new upload object if file not found
                    if (!is_object($upload)) {
                        $upload = new Upload;
                        $upload->save();
                    }
                    ## Update upload record with new path
                    $upload->update(array(
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'module' => $module,
                        'unit_id' => $unit_id,
                    ));

                    ## Return
                    return @$upload->$return;

                }

                ## Return exist upload_id, if no actions
                if (is_numeric($upload_id))
                    return $upload_id;
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
    }

    /****************************************************************************/
    
	public function __construct(){
		
	}

    /****************************************************************************/

}


