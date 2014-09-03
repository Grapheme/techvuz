<?php

class AdminLocaleEditorController extends BaseController {

    public static $name = 'locale_editor';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        $name = self::$name;
        $group = self::$group;
        Route::group(array('before' => 'auth', 'prefix' => 'admin'), function() use ($class, $name, $group) {
        	Route::controller($group . '/' . $name, $class);
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
            'tpl' => static::returnTpl('admin/locale_editor'),
            'gtpl' => static::returnTpl(),

            'class' => __CLASS__,
        );
        View::share('module', $this->module);
	}

    public function getIndex() {

        $locales = Config::get('app.locales');
        return View::make($this->module['tpl'].'index', compact('locales'));
    }

    public function getList() {

        $locales = Config::get('app.locales');
        $dirs = glob(app_path('lang/*'), GLOB_ONLYDIR);
        $files = array();
        $all_files = array();
        foreach ($dirs as $dir) {
            $glob = glob($dir.'/*');
            $glob = array_flip($glob);
            $files[$dir] = $glob;
            foreach ($files[$dir] as $file => $null) {
                $file = basename($file);
                $all_files[$file] = 1;
            }
        }
        #Helper::d($dirs);
        #Helper::d($files);
        #Helper::d($all_files);

        return View::make($this->module['tpl'].'list', compact('locales', 'dirs', 'files', 'all_files'));
    }

    public function getEdit($locale_sign) {

        $locales = Config::get('app.locales');
        return View::make($this->module['tpl'].'index', compact('locales'));
    }

    public function postSaveLocales() {

        $langs = Input::get('lang');
        #Helper::d($langs);

        $json_request = array('status' => FALSE, 'responseText' => '');

        if (!is_array($langs) || !count($langs)) {
            $json_request['responseText'] = "Нет данных для записи";
            return Response::json($json_request, 200);
            #return 'error';
        }

        $files_total = 0;
        $files_writed = 0;

        foreach ($langs as $locale_sign => $files) {
            $dir = app_path('lang/'.$locale_sign);
            if (!file_exists($dir) && !is_dir($dir)) {
                @mkdir($dir);
                @chmod($dir, 0777);
            }
            if (is_dir($dir) && is_writable($dir) && is_array($files) && count($files)) {
                foreach($files as $filename => $vars) {
                    ++$files_total;
                    $file = $dir . '/' . $filename;
                    if (!file_exists($file)) {
                        @touch($file);
                        @chmod($file, 0777);
                    }
                    if (file_exists($file) && is_writable($file) && is_array($vars) && count($vars)) {
                        $lines = array('<?php', '', 'return array(');
                        foreach($vars as $key => $val) {
                            if (mb_strpos($val, "'")) {
                                $val = preg_replace("~'~is", '\'', $val);
                            }
                            $lines[] = "    '" . $key . "' => '" . $val . "',";
                        }
                        $lines[] = ');';
                        $lines = implode("\n", $lines);
                        #Helper::dd($lines);
                        @file_put_contents($file, $lines);
                        ++$files_writed;
                    } else {
                        $json_request['responseText'] = "Не могу создать файл: " . basename($dir) . '/' . $filename;
                        break(2);
                    }
                }
            } else {
                $json_request['responseText'] = "Не могу создать директорию: " . basename($dir);
                break;
            }
            #Helper::dd($dir);
        }

        if ($files_total == $files_writed) {
            $json_request['status'] = true;
        }

        return Response::json($json_request, 200);

    }
}
