<?php

class AdminSystemController extends BaseController {

    public static $name = 'system';
    public static $group = 'system';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        /*
        $class = __CLASS__;
        Route::group(array('before' => 'auth', 'prefix' => $prefix), function() use ($class) {
        	Route::controller($class::$group, $class);
        });
        */
    }

    ## Actions of module (for distribution rights of users)
    ## return false;   # for loading default actions from config
    ## return array(); # no rules will be loaded
    public static function returnActions() {
        return array(
            'system'        => 'Глобальный доступ к работе с настройками',
            'modules'       => 'Работа с модулями',
            'groups'        => 'Работа с группами пользователей',
            'users'         => 'Работа с пользователями',
            'locale_editor' => 'Работа с редактором языков',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => '<i class="fa fa-exclamation-triangle"></i> Система',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {

        $menu = array();
        $menu_child = array();

        if (Allow::action('system', 'modules', false, true))
            $menu_child[] = array(
                'title' => 'Модули',
                'link' => 'system/modules',
                'class' => 'fa-gears',
            );

        if (Allow::action('system', 'groups', false, true))
            $menu_child[] = array(
                'title' => 'Группы',
                'link' => 'system/groups',
                'class' => 'fa-group',
            );

        if (Allow::action('system', 'users', false, true))
            $menu_child[] = array(
                'title' => 'Пользователи',
                'link' => 'system/users',
                'class' => 'fa-user',
            );

        if (Allow::action('system', 'locale_editor', false, true))
            $menu_child[] = array(
                'title' => 'Редактор языков',
                'link' => 'system/locale_editor',
                'class' => 'fa-language',
            );

        if (count($menu_child) && Allow::action('system', 'system', false, true))
            $menu[] = array(
                'title' => 'Настройки',
                'link' => '#',
                'class' => 'fa-gear',
                'system' => 1,
                'menu_child' => $menu_child,
            );

        return $menu;
    }

    /****************************************************************************/

	public function __construct(){
        #
	}
    
}
