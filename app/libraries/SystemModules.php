<?php

class SystemModules {

	public static function getSidebarModules(){

		$start_page = AuthAccount::getStartPage();

        $menu = array();
        
        ## Modules info
        $mod_info = Config::get('mod_info');
        $mod_menu = Config::get('mod_menu');
        #Helper::dd($mod_info);
        #Helper::d($mod_menu);

        $entity_dics = Dic::where('entity', '1')->orderBy('order', 'ASC')->get();
        #Helper::tad($entity_dics);
        if (count($entity_dics)) {
            $dic_entities = array();
            foreach ($entity_dics as $entity_dic) {
                $dic_entities[$entity_dic->slug] = array(array(
                    'title' => $entity_dic->name,
                    #'link' => Helper::clearModuleLink(URL::route('dicval.index', $entity_dic->id)),
                    'link' => Helper::clearModuleLink(URL::route('entity.index', $entity_dic->slug)),
                    'class' => $entity_dic->icon_class,
                    'module' => 'dictionaries',
                    'permit' => 'dicval',
                ));
            }
            $dic_entities += $mod_menu;
            $mod_menu = $dic_entities;
            #Helper::d($dic_entities);
            #Helper::dd($mod_menu);
        }

        ## If exists menu elements...
        if (isset($mod_menu) && is_array($mod_menu) && count($mod_menu)) {
            #foreach( $mod_menu as $mod_name => $menu_elements ) {
            foreach( Allow::modules() as $mod_name => $module ) {

                $menu_elements = $mod_menu[$mod_name];

                if( is_array($menu_elements) && count($menu_elements) ) {

                    #Helper::d($mod_name); #continue;
                    #Helper::d($menu_elements); #continue;

                    foreach( $menu_elements as $m => $menu_element ) {

                        #Helper::d($menu_element); #continue;

                        ## If permit to view menu element
                        $rules = @$menu_element['permit'];
                        $module = @$menu_element['module'] ?: $mod_name;
                        $permit = $rules ? Allow::action($module, $rules, true, true) : true;

                        #Helper::d($module . " :: " . $permit);
                        #Helper::d( $menu_element['title'] . " - " . (int)$permit );

                        if ($permit)
                            $menu[] = $menu_element;
                    }
                }
            }
        }
        #Helper::dd($menu);
        return $menu;
	}

	/*
	| Функция возвращает всю запись о модуле.
	| Если Модуль не существует - возвращается TRUE, это нужно для возможности дальнейшей проверки на уровне ролей групп пользователей
	| Allow::valid_access()
	*/
	public static function getModules($name = NULL, $index = NULL){

        ## mod_info - информация о модулях, загружается в routes.php
        $modules = array();
        $mod_info = Config::get('mod_info');
        #Helper::dd($mod_info);

        foreach( $mod_info as $mod_name => $info ) {
            if (
                @$info['visible']
                #|| @$info['show_in_menu']
            )
                $modules[$mod_name] = $info;
        }
        #Helper::dd($modules);

		if(is_null($name)):
			return $modules;
		else:
			if(isset($modules[$name])):
				if(is_null($index)):
					return $modules[$name];
				elseif(isset($modules[$name][$index])):
					return $modules[$name][$index];
				endif;
			else:
				return TRUE;
			endif;
		endif;
	}
}
