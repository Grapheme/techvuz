<?php

class AccountsMessagesController extends BaseController {

    public static $name = 'messages';
    public static $group = 'accounts';
    public static $entity = 'messages';
    public static $entity_name = 'Уведомления';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        $slug = 'types-system-messages';
        if (Allow::module('dictionaries')):
            if(Dictionary::where('slug',$slug)->exists()):
                self::messages($slug);
            endif;
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

    private static function messages($slug){

        if($routeActions = Dictionary::where('slug',$slug)->first()->values()->where('version_of',NULL)->groupBy('slug')->get()):
            Helper::tad($routeActions);
            foreach ($routeActions as $action):
                $action_id = $action->id;
                $action_slug = $action->slug;
                $messageText = $action->name;
                Event::listen($action_slug, function ($data) use ($action_id,$action_slug,$messageText) {
                    $actionDate = date("Y-m-d H:i:s");
                    foreach($data as $index => $value):
                        $messageText = self::setValue($index,$value,$messageText);
                    endforeach;
                    DicVal::inject('system-messages', array(
                        'slug' => $action_slug.'.'.$actionDate,
                        'name' => $messageText,
                        'fields' => array(
                            'user_id' => $data['accountID'],
                            'action_id' => $action_id,
                            'created_time' => $actionDate,
                        )
                    ));
                });
            endforeach;
        endif;
    }

    private static function setValue($search, $replace,$text) {
        return str_replace('['.$search.']', $replace, $text);
    }
}