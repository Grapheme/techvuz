<?php

class AdminEducationMenuController extends BaseController {

    public static $name = 'education';
    public static $group = 'education';
    public static $entity = 'directions';
    public static $entity_name = 'Обучение';

    /****************************************************************************/

    public static function returnRoutes() {

    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return array(
            'view'   => 'Просмотр',
            'create' => 'Создание',
            'edit'   => 'Редактирование',
            'delete' => 'Удаление',
        );
    }

    public static function returnInfo() {
        return array(
            'name' => self::$name,
            'group' => self::$group,
            'title' => 'Обучение',
            'visible' => 1,
        );
    }

    public static function returnMenu() {
        return array(
            array(
                'title' => 'Обучение',
                'link' => self::$group.'/'.AdminEducationDirectionsController::$name,
                'class' => 'fa-book',
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/

    public function __construct(){

    }

}