<?php

class AccountsModeratorController extends BaseController {

    public static $name = 'moderator';
    public static $group = 'accounts';
    public static $entity = 'moderator';
    public static $entity_name = 'Кабинет модератор';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;

        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function() use ($class) {
                Route::get('companies', array('as' => 'moderator-companies-list', 'uses' => $class . '@CompaniesList'));
                Route::get('orders', array('as' => 'moderator-orders-list', 'uses' => $class . '@OrdersList'));
                Route::get('order/{order_id}/extended', array('as' => 'moderator-order-extended', 'uses' => $class . '@OrderExtendedView'));
                Route::get('listeners', array('as' => 'moderator-listeners-list', 'uses' => $class . '@ListenersList'));
            });
        endif;
    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return NULL;
    }

    public static function returnInfo() {
        return NULL;
    }

    public static function returnMenu() {
        return NULL;
    }

    /****************************************************************************/

    public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }
    /****************************************************************************/
    /******************************* КОМПАНИИ ***********************************/
    /****************************************************************************/
    public function CompaniesList(){

        $page_data = array(
            'page_title'=> 'Список компаний',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('companies'),$page_data);
    }
    /****************************************************************************/
    /******************************** ЗАКАЗЫ ************************************/
    /****************************************************************************/
    public function OrdersList(){

        $page_data = array(
            'page_title'=> 'Список заказов',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('orders'),$page_data);
    }

    public function OrderExtendedView(){

        $page_data = array(
            'page_title'=> 'Просмотр заказа',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('order'),$page_data);
    }
    /****************************************************************************/
    /****************************** СЛУШАТЕЛИ ***********************************/
    /****************************************************************************/
    public function ListenersList(){

        $page_data = array(
            'page_title'=> 'Список слушателей',
            'page_description'=> '',
            'page_keywords'=> '',
        );
        return View::make(Helper::acclayout('listeners'),$page_data);
    }
}