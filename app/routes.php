<?php

$prefix = AuthAccount::getGroupStartUrl();

Route::get('redactor/get-uploaded-images', 'DownloadsController@redactorUploadedImages');
Route::post('redactor/upload', 'DownloadsController@redactorUploadImage');

Route::get('sitemap',function(){

    $xml = File::get(public_path('sitemap.xml'));
    $response = Response::make($xml, 200);
    $response->header('Content-Type', 'text/xml');
    return $response;
});

#################################################################
## Все, что ниже - можно вынести в модуль system - Пользователи.
## Но, возможно, придется следить за порядком загрузки модулей...
#################################################################

Route::group(array('before' => 'auth.status', 'prefix' => $prefix), function() use ($prefix){

    Route::get('/', function() use ($prefix){
        $controller = new BaseController;
        return $controller->dashboard($prefix);
    });
});

/*
| Роуты, доступные только для неавторизованных пользователей
*/
Route::group(array('before' => 'guest.auth', 'prefix' => ''), function(){
	Route::post('signin', array('as' => 'signin', 'uses' => 'GlobalController@signin'));
	Route::post('signup', array('as' => 'signup', 'uses' => 'GlobalController@signup'));
	#Route::get('activation', array('as' => 'activation', 'uses' => 'GlobalController@activation'));
    Route::resource('restore-password','RemindersController',
        array(
            'only'=>array('index','store','show','update'),
            'names' => array(
                'index'  => 'password-reset.create',
                'store'  => 'password-reset.store',
                'show'   => 'password-reset.show',
                'update' => 'password-reset.update',
            )
        )
    );

});

/*
| Роуты, доступные для гостей и авторизованных пользователей
*/
Route::get('login', array('before' => 'login', 'as' => 'login', 'uses' => 'GlobalController@loginPage'));
Route::get('logout', array('as' => 'logout', 'uses' => 'GlobalController@logout'));

Route::post('check-email',array('as' => 'check-email', 'uses' => 'GlobalController@checkEmail'));

#################################################################


/***********************************************************************/
/******************** ЗАГРУЗКА РЕСУРСОВ ИЗ МОДУЛЕЙ *********************/
/***********************************************************************/
## For debug
$load_debug = 0;
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
$mod_new = array();
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

    if (!empty($module_prefix) && $module_prefix != 'public' && Auth::check()):
        if ($module_name == 'galleries '):
            $module_prefix = 'admin';
        else:
            $module_prefix = AuthAccount::getGroupName();
        endif;
    endif;

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
            #$mod_info[$module_name] = $module_fullname::$returnInfo();

            $module_info = $module_fullname::$returnInfo();
            if (!$module_info)
                continue;

            $mod_info[$module_name] = $module_info;

            $module = new Module;
            $module->name = $module_info['name'];
            $module->on = 0;
            $module->order = NULL;

            $mod_new[$module_name] = $module;

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

        if ($load_debug) echo " CLASS NOT FOUND: {$module_fullname} | composer dump-autoload OR php-file has unusual codepage OR file name start with DIGIT!";
        
    }
    
    if ($load_debug) echo "<br/>\n";
}

Config::set('mod_info', $mod_info);
Config::set('mod_actions', $mod_actions);
Config::set('mod_menu', $mod_menu);
Config::set('mod_new', $mod_new);

/*Route::group(array('before' => 'admin.auth', 'prefix' => 'admin'), function(){
    Route::get('clear/db/{code}', function($code){
        if ($code == Config::get('app.key')):
            $index = 1;
            foreach(DicFieldVal::all() as $dicFieldVal):
                DicFieldVal::where('id',$dicFieldVal->id)->update(array('id'=>$index++));
            endforeach;
            $index = 1;
            foreach(DicVal::all() as $dicVal):
                DicVal::where('id',$dicVal->id)->update(array('id'=>$index));
                DicFieldVal::where('dicval_id',$dicVal->id)->update(array('dicval_id'=>$index));
                $index++;
            endforeach;
            $IDs = DicVal::where('dic_id',2)->lists('id');
            if (count($IDs)):
                DicFieldVal::whereIn('dicval_id',$IDs)->delete();
                DicVal::where('dic_id',2)->delete();
            endif;
            $IDs = DicVal::where('dic_id',8)->lists('id');
            if (count($IDs)):
                DicFieldVal::whereIn('dicval_id',$IDs)->delete();
                DicVal::where('dic_id',8)->delete();
            endif;
        endif;
    });
}); */

/***********************************************************************/


	#Route::controller('/admin/videogid/dic/{learning_forms}', 'AdminVideogidDicsController');
    #Route::resource('/admin/videogid/dic', 'AdminVideogidDicsController');
    #Route::controller('', 'PublicVideogidController');
    #Route::controller('', 'PublicVideogidController');