<?php

class AdminCollectorsController extends BaseController {

    public static $name = 'collectors';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;

        Route::group(array('before' => 'auth', 'prefix' => 'admin'), function() use ($class, $name, $group) {
            Route::get('scripts.js', array('as' => 'collectors.js', 'uses' => $class.'@getCollectorScripts'));
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
		

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'tpl' => static::returnTpl('admin/modules'),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
	}
	
	public function getCollectorScripts(){

        #$modules = Module::where('on', '1')->orderBy('order', 'ASC')->orderBy('updated_at', 'ASC')->get();
        #Helper::tad($modules);


        ## File with times of modification each JS
        $mods_file = storage_path('modules_js_modifications.dat');
        ## Cache of the last compile JS
        $js_cache_file = storage_path('modules_js_cache.dat');

        ## All JS files of the ALL modules
        $files = glob(app_path('modules/*/js/*.js'));
        #Helper::d($files);
        $mod = @json_decode(file_get_contents($mods_file), 1);
        #Helper::d($mod);

        $need_reload = false;
        foreach ($files as $file) {
            #Helper::dd($file);
            $mtime = @filemtime($file);
            #Helper::d($file . " - " . $mtime . " - " . $mod[$file]);
            if ($mtime != @$mod[$file]) {
                $need_reload = true;
                break;
            }
        }

        #Helper::d((int)$need_reload);

        if ($need_reload) {
            $mods = array();
            $js = "";
            foreach ($files as $file) {
                $mtime = @filemtime($file);
                $mods[$file] = $mtime;
                $data = @file_get_contents($file);
                $js .= "/*** " . basename($file) . " ***/\n\n" . $data . "\n\n";
            }
            @file_put_contents($mods_file, json_encode($mods));
            $js = trim($js);
            file_put_contents($js_cache_file, $js);
            $prefix = "/*** FROM FILES ***/\n\n";
        } else {
            $prefix = "/*** FROM CACHE ***/\n\n";
            $js = @file_get_contents($js_cache_file);
        }

        #header('Content-Type: application/javascript');

        return Response::make(@$prefix . @$js, 200, array('Content-Type' => 'application/javascript'));
    }
}
