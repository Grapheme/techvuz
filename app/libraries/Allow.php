<?php

class Allow {

    private static $modules;
    private static $actions;

    private static function init() {
        if (!self::$modules) {
            self::$modules = array();
            $temp = Module::where('on', '1')->with('actions')->get();
            foreach ($temp as $tmp) {
                $actions = array();
                foreach ($tmp->actions as $action) {
                    #Helper::d($action);
                    $actions[$action->action] = $action;
                }
                $tmp->actions = $actions;
                self::$modules[$tmp['name']] = $tmp;
            }
            #self::$modules['system'] = array('system' => 1);
        }
        #Helper::dd(self::$modules);
    }

	/**
	 * Проверяет, есть ли у текущего авторизованного пользователя разрешение на выполнение определенного действия в модуле
     * Рекомендуется использовать в шаблонах, например при выводе ссылок на страницы редактирования/удаления.
     *
     * @param string $module_name
     * @param string $action
     * @param boolean $check_module_enabled
     * @param boolean $admin_grants_all
     * @return bool
	 */
	public static function action($module_name, $action, $check_module_enabled = true, $admin_grants_all = true){

        self::init();

		$access = false;
        ## Check auth
		if(Auth::check()) {
            ## Get current user group
            $user_group = Auth::user()->group;

            #Helper::dd(@self::$modules);
            #Helper::d(@self::$modules[$module_name]);

            if (!$check_module_enabled || isset(self::$modules[$module_name]) || @self::$modules[$module_name]['system']) {

                /**
                 * @todo Полные права на действия админа, т.к. новые имена модулей не совпадают со старыми ролями ( news != admin_news ). Нужно во всех модулях поменять valid_action_permission на Action
                 */
                ## Grants all to ADMIN
                if ($user_group->id == '1' && $admin_grants_all) {

                    $access = true;

                } else {

                    $module = isset(self::$modules[$module_name]) ? self::$modules[$module_name] : null;

                    #if ($module_name == 'system')
                    #    Helper::dd($module->actions);

                    ## Check all conditions
                    if(!is_null($user_group) && !is_null($module) && !is_null($action)) {
                        ## If user group is not ADMIN
                        $permission = isset($module->actions[$action]) ? $module->actions[$action] : null;
                        ## If permission exists & is activated
                        if (!is_null($permission) && $permission->status == '1') {
                            $access = true;
                        }
                    } else {
                        #Helper::d($user_group . " / " . $module_name . " = " . (int)$module . " / " . $action);
                    }
                }
            }
		}
        #Helper::d($module_name . " @ " . $action . " = " . $access);
		return $access;
	}

	public static function menu($module_name){

        return self::action($module_name, 'view', false, false);
    }

	/**
	 * Прерывает выполнение дальнейших действий, если у пользователя нет прав на выполнение конкретного действия в модуле.
     * Рекомендуется использовать в самом начале защищенного метода класса, например при редактировании или удалении данных.
     *
     * @param string $module_name
     * @param string $action
     * @return ABORT
	 */
	public static function permission($module_name, $action){

        if (!self::action($module_name, $action, false))
            App::abort(403);
    }

	/**
	 * Проверяет, доступен ли модуль (включен ли он).
     * Рекомендуется использовать в шаблонах, при подключении элементов расширенной формы ExtForm::<element>
     *
     * @param string $module_name
     * @return bool
	 */
	public static function module($module_name){

        #return (bool)(Module::where('name', $module_name)->exists() && Module::where('name', $module_name)->first()->on == 1);

        self::init();

        #Helper::dd(self::$modules);

        #if (@self::$modules[$module_name])
        #    Helper::dd(self::$modules[$module_name]);

        #Helper::d($module_name . ' => ' . isset(self::$modules[$module_name]));

        return (bool)(
            isset(self::$modules[$module_name])
            && (
                (is_object(self::$modules[$module_name]) && self::$modules[$module_name]->on == '1')
                || @self::$modules[$module_name]['system']
            )
        );
	}

    /**
     * Проверяет, является ли пользователем Суперюзером (состоит в группе Администраторы).
     * Обращается к заведомо несуществующему методу несуществующего модуля, без проверки активности модуля, с предоставлением полного доступа Администраторам.
     *
     * @return bool
     */
    public static function superuser() {
        return self::action('undefined_module', 'undefined_action', false, true);
    }

    /**
     * Alias for Allow::module(<module_name>);
     */
	public static function enabled_module($module_name){
        return self::module($module_name);
	}
    /**
     * Alias for Allow::module(<module_name>);
     */
	public static function valid_access($module_name){
        return self::module($module_name);
	}

}
