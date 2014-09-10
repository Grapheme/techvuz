<?php

class AdminPagesMenuController extends BaseController {

    public static $name = 'pages';
    public static $group = 'pages';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
    }

    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
            'view'     => 'Просмотр',
            'create'   => 'Создание',
            'edit'     => 'Редактирование',
            'delete'   => 'Удаление',
            'settings' => 'Настройка модуля',
        );
    }

    ## Settings of module
    public static function returnSettings() {
        return array(
            'route_mainpage' => array(
                'name'    => 'Маршрут для главной страницы',
                'desc'    => 'Создавать-ли маршрут для главной страницы?',
                'type'    => 'bool',
                'default' => 1,
            ),
            'route_pages' => array(
                'name'    => 'Маршруты для страниц',
                'desc'    => 'Создавать-ли маршруты для всех остальных страниц?',
                'type'    => 'bool',
                'default' => 1,
            ),
            'missed_pages_direct_tpl_loading' => array(
                'name'    => 'Прямая загрузка несуществующих страниц из шаблонов',
                'desc'    => 'Если посетитель пытается открыть страницу, для которой не создана запись в Панели Администратора, модуль будет пытаться загрузить файл шаблона, имя которого совпадает с URL запрошенной страницы',
                'type'    => 'bool',
                'default' => 1,
            ),
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
        	'title' => 'Страницы',
            'visible' => 1,
        );
    }

    ## Menu elements of the module
    public static function returnMenu() {
        return array(
            array(
            	'title' => 'Страницы',
                'link' => self::$group,
                'class' => 'fa-list-alt', 
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/

}