<?php

class AccountsOrganizationController extends BaseController {

    public static $name = 'organization';
    public static $group = 'accounts';
    public static $entity = 'organization';
    public static $entity_name = 'Действия организации';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        if (Auth::check() ):
            Route::group(array('before' => 'guest.status', 'prefix' => Auth::user()->group()->pluck('name')), function() use ($class) {
                Route::post('ordering/courses-store', array('as'=>'ordering-courses-store', 'uses' => $class.'@OrderingCoursesStore'));
                Route::get('ordering/select-listeners', array('as'=>'ordering-select-listeners', 'uses' => $class.'@OrderingSelectListeners'));
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

    public function OrderingCoursesStore(){

        print_r(Input::all());
        exit;
    }

    public function OrderingSelectListeners(){

        print_r('YES');
        exit;
    }
}