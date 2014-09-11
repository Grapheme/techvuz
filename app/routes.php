<?php

$prefix = 'guest';
if(Auth::check()):
	$prefix = AuthAccount::getStartPage();
endif;
	/*
	| Общие роуты, независящие от условий
	*/

Route::get('redactor/get-uploaded-images', 'DownloadsController@redactorUploadedImages');
Route::post('redactor/upload','DownloadsController@redactorUploadImage');

	/*
	| Роуты, доступные для всех групп авторизованных пользователей
	*/

Route::group(array('before'=>'auth', 'prefix'=>$prefix), function(){
	Route::controller('downloads', 'DownloadsController');
});

	/*
	| Роуты, доступные для группы Администраторы
	*/

Route::group(array('before'=>'auth', 'prefix'=>'admin'), function(){

	Route::get('/', 'BaseController@dashboard');
});

	/*
	| Роуты, доступные для группы Пользователи
	*/
/*
Route::group(array('before'=>'user.auth', 'prefix'=>'dashboard'), function(){
	Route::get('/', 'UserCabinetController@mainPage');
});
*/
	/*
	| Роуты, доступные только для неавторизованных пользователей
	*/

Route::group(array('before'=>'guest', 'prefix'=>Config::get('app.local')), function(){
	Route::post('signin', array('as'=>'signin', 'uses'=>'GlobalController@signin'));
	Route::post('signup', array('as'=>'signup', 'uses'=>'GlobalController@signup'));
	Route::get('activation', array('as'=>'activation', 'uses'=>'GlobalController@activation'));
});

	/*
	| Роуты, доступные для гостей и авторизованных пользователей
	*/
##Route::post('request-to-access', array('as'=>'request-to-access', 'uses'=>'GlobalController@postRequestToAccess'));
Route::get('login', array('before'=>'login', 'as'=>'login', 'uses'=>'GlobalController@loginPage'));
Route::get('logout', array('before'=>'auth', 'as'=>'logout', 'uses'=>'GlobalController@logout'));

/***********************************************************************/
/******************** ЗАГРУЗКА РЕСУРСОВ ИЗ МОДУЛЕЙ *********************/
/***********************************************************************/
## For debug
$load_debug = FALSE;
## Reserved methods for return resourses of controller
$returnRoutes = "returnRoutes";
$returnActions = "returnActions";
$returnShortCodes = "returnShortCodes";
$returnExtFormElements = "returnExtFormElements";
$returnInfo = "returnInfo";
$returnMenu = "returnMenu";
## Find all controllers & load him resoures: routes, shortcodes & others...
$postfix = ".controller.php";
$mod_path = "../app/modules/*/*".$postfix;
$files = glob($mod_path);
#print_r($files); die;
## Work with each module
$mod_actions = array();
$mod_info = array();
$mod_menu = array();
$default_actions = Config::get('actions');
foreach ($files as $file) {

    #$dir_name = basename(dirname($file));

    $file_name = basename($file);
    $tmp_module_name = $module_name = str_replace($postfix, "", $file_name);
    
    if (strpos($module_name, ".")) {
        $blocks = explode(".", $module_name);
        foreach ($blocks as $b => $block) {
            $blocks[$b] = ucfirst($block);
        }
        $module_name = implode("", $blocks);
    }
    
    $module_prefix = "";
    $module_postname = $module_name;
    if (strpos($module_name, "_"))
        list($module_prefix, $module_postname) = explode("_", $module_name, 2);
    $module_prefix = strtolower($module_prefix);

    $module_fullname = ucfirst($module_prefix).ucfirst($module_postname)."Controller";

    if ($load_debug)
        echo $file_name . ": " . $module_prefix . " | " . $module_name . " | " . $module_fullname . " > "; #die;

    ## If class have right name...
    if (class_exists($module_fullname)) {

        ## Load routes...
        if (method_exists($module_fullname, $returnRoutes)) {
            if ($load_debug) echo " [ load routes... ] ";
            $module_fullname::$returnRoutes($module_prefix);
        }
        ## Load shortcodes...
        if (method_exists($module_fullname, $returnShortCodes)) {
            if ($load_debug) echo " [ load shortcodes... ] ";
            $module_fullname::$returnShortCodes();
        }
        ## Load Extended Form elements...
        if (method_exists($module_fullname, $returnExtFormElements)) {
            if ($load_debug) echo " [ load extended form elements... ] ";
            $module_fullname::$returnExtFormElements();
        }
        
        #if (!isset($module_fullname::$name))
        #    continue;

        ## Get module name...
        $module_name = $module_fullname::$name;

        ## Load module info...
        if (method_exists($module_fullname, $returnInfo)) {
            if ($load_debug) echo " [ load info... ] ";
            $mod_info[$module_name] = $module_fullname::$returnInfo();
        }
        
        ## Load module actions...
        $actions = array();
        if (method_exists($module_fullname, $returnActions)) {
            if ($load_debug) echo " [ load actions... ] ";
            $actions = $module_fullname::$returnActions();
        }
        #$mod_actions[$module_name] = $actions === false ? $default_actions : $actions; #array_merge($default_actions, $actions);
        $mod_actions[$module_name] = $actions;

        ## Load module admin menu elements...
        if (method_exists($module_fullname, $returnMenu)) {
            if ($load_debug) echo " [ load menus... ] ";
            $mod_menu[$module_name] = $module_fullname::$returnMenu();
        }

    } else {

        if ($load_debug) echo " CLASS NOT FOUND: {$module_fullname} | composer dump-autoload OR file name start with DIGIT!";
        
    }
    
    if ($load_debug) echo "<br/>\n";
}
#Helper::dd($mod_actions);

/*
foreach ($mod_actions as $module_name => $actions) {
    if (!count($actions))
        continue;
    $title = isset($mod_info[$module_name]['title']) ? $mod_info[$module_name]['title'] : $module_name;
    echo "<h2>{$title} - ОТКЛЮЧИТЬ МОДУЛЬ ДЛЯ ТЕКУЩЕЙ ГРУППЫ | РАЗРЕШИТЬ / ЗАПРЕТИТЬ ВСЕ ДЕЙСТВИЯ</h2>\n";
    foreach ($actions as $a => $action) {
        echo "<p>{$action} - РАЗРЕШЕНО / ЗАПРЕЩЕНО</p>";
    }
}
*/
#Helper::dd($mod_info);
#Helper::dd($mod_actions);
#Helper::dd($mod_menu);

Config::set('mod_info', $mod_info);
Config::set('mod_actions', $mod_actions);
Config::set('mod_menu', $mod_menu);
#View::share('mod_actions', $mod_actions);
#print_r($app);

/***********************************************************************/

if (Auth::check() && Allow::module('dictionaries')):
    if(Dictionary::where('slug','actions_types')->exists()):
        if($routeActions = Dictionary::where('slug','actions_types')->first()->values()->get()):
            foreach ($routeActions as $action):
                $action_id = $action->id;
                $action_slug = $action->slug;
                Event::listen($action_slug, function ($data) use ($action_id,$action_slug) {
                    $actionDate = date("Y-m-d H:i:s");
                    $nickname = 'Событие за '.myDateTime::SwapDotDateWithTime($actionDate);
                    $link = NULL;
                    if (isset($data['title'])):
                        $nickname =  $data['title'];
                    endif;
                    if (isset($data['link'])):
                        $link =  $data['link'];
                    endif;
                    DicVal::inject('actions_history', array(
                        'slug' => $action_slug.'.'.$actionDate,
                        'name' => $nickname,
                        'fields' => array(
                            'user_id' => Auth::user()->id,
                            'action_id' => $action_id,
                            'title' => $nickname,
                            'link' => $link,
                            'created_time' => $actionDate,
                        )
                    ));
                });
            endforeach;
        endif;
    endif;
endif;