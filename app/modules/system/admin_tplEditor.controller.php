<?php

class AdminTplEditorController extends BaseController {

    public static $name = 'tpl_editor';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;
        Route::group(array('before' => 'auth', 'prefix' => 'admin'), function() use ($class, $name, $group) {
            Route::get($group . '/' . $name . '/edit/{mod}', $class . '@getEdit');
            Route::get($group . '/' . $name . '/save/{mod}', $class . '@postSave');
            Route::controller($group . '/' . $name, $class);
        });

        ModTemplates::addTplDir(); ## Site layout dir
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
            'tpl' => static::returnTpl('admin/tpl_editor'),
            'gtpl' => static::returnTpl(),

            'class' => __CLASS__,
        );
        View::share('module', $this->module);
	}

    public function getIndex() {

        $modules = Config::get('mod_info');
        $modules['layout'] = array('title' => 'Шаблон сайта');
        #Helper::dd($modules);

        $templates = ModTemplates::get();
        #Helper::dd($templates);

        return View::make($this->module['tpl'].'index', compact('modules', 'templates'))->render();
    }

    public function getEdit($mod_name) {

        #Helper::d($mod_name);
        #Helper::dd(Input::all());

        $file = Input::get('tpl');

        if ($mod_name == 'layout') {
            $mod = array('title' => 'Шаблон сайта');
            $file0 = 'views/templates/'.Config::get('app.template').'/'.$file.'.blade.php';
        } else {
            $mod = Config::get('mod_info.'.$mod_name);
            $file0 = 'modules/'.$mod_name.'/views/'.$file.'.blade.php';
        }
        $full_file = app_path($file0);
        $file0 = 'app/' . $file0;

        #Helper::d($module);
        #Helper::dd($full_file);

        #$tpl_exists = file_exists($full_file);
        #Helper::dd($tpl_exists);

        return View::make($this->module['tpl'].'edit', compact('mod_name', 'file', 'file0', 'mod', 'full_file'));
    }

    public function postSave($mod_name) {

        #Helper::dd(Input::all());

        $json_request = array('status' => FALSE, 'responseText' => '');

        $file = Input::get('file');
        $tpl = Input::get('tpl');

        if ($tpl === '') {
            return Response::json($json_request, 200);
        }

        if ($mod_name == 'layout') {
            $full_file = app_path('views/templates/' . Config::get('app.template') . '/' . $file . '.blade.php');
        } else {
            $full_file = app_path('modules/' . $mod_name . '/views/' . $file . '.blade.php');
        }

        $result = @file_put_contents($full_file, $tpl);

        /**
         * Send changes to GitHub
         */

        $config = Config::get('github');
        if ($config['active'] != FALSE && Input::get('git') && class_exists('GitHub')) {

            #if($config['test_mode_key'] == $extends):
                $config['test_mode'] = TRUE;
            #else:
            #    $config['test_mode'] = FALSE;
            #endif;

            $config['set_log'] = FALSE;

            $github = new GitHub();
            $github->init($config);
            $result = $github->execute('git add ' . $full_file);
            #echo $result . "\n";
            if ($result == 0) {
                $result = $github->execute('git commit -m "server commit - template editor; module: ' . $mod_name . ', file: ' . $file . '"');
                #echo $result . "\n";
                if ($result == 0) {
                    $result = $github->pull();
                    #echo $result . "\n";
                    $result = $github->push();
                    #echo $result . "\n";
                }
            }
        }

        $json_request['status'] = true;
        return Response::json($json_request, 200);
    }

}
