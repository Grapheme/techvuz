<?php

class AdminDicsMenuController extends BaseController {

    public static $name = 'dictionaries';
    public static $group = 'dictionaries';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {
        ##
    }

    ## Shortcodes of module
    public static function returnShortCodes() {
        ##
    }
    
    ## Actions of module (for distribution rights of users)
    public static function returnActions() {
        return array(
        	'view'   => 'Отображать в меню',
            'create' => 'Создание словарей',
            'edit'   => 'Изменение словарей',
            'delete' => 'Удаление словарей',

            'dicval' => 'Работа с содержимым словарей',
        );
    }

    ## Info about module (now only for admin dashboard & menu)
    public static function returnInfo() {
        return array(
        	'name' => self::$name,
        	'group' => self::$group,
            'title' => 'Словари',
            'visible' => '1',
        );
    }


    public static function returnMenu() {

        ## Without child links
        return array(
            array(
            	'title' => 'Словари',
                'link' => self::$group . '/dic',
                #'link' => URL::route('dic.index'),
                'class' => 'fa-bars',
                'permit' => 'view',
            ),
        );
        #*/
    }
        
    /****************************************************************************/
    
	public function __construct(){
        ##
	}
}


