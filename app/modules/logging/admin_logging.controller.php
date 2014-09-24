<?php

class AdminLoggingController extends BaseController {

    public static $name = 'logging';
    public static $group = 'logging';
    public static $entity = 'logging';
    public static $entity_name = 'Логирование действий';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {

        $slug = 'actions-types';
        if (Auth::check() && Allow::action(self::$group,'logging',true,false) && Allow::module('dictionaries')):
            if(Dictionary::where('slug',$slug)->exists()):
                self::events($slug);
            endif;
        endif;
    }

    public static function returnShortCodes() {
    }

    public static function returnActions() {
        return array(
            'logging'  => 'Вести лог действий данной группы',
        );
    }
    public static function returnInfo() {
        return array(
            'name' => self::$name,
            'group' => self::$group,
            'title' => 'Логирование действий',
            'visible' => '1',
        );
    }

    public static function returnMenu() {
    }

    /****************************************************************************/

	public function __construct(){

	}

    private static function events($slug){

        if($routeActions = Dictionary::where('slug',$slug)->first()->values()->get()):
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
                    DicVal::inject('actions-history', array(
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
    }
}


