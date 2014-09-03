<?
    $page_title = "Группы пользователей";


    $menus = array();

    if (Allow::action($module['group'], 'permissions')) {
        $menus[] = array(
            'link' => mb_substr(action($module['class'] . '@getIndex'), 0, -6),
            'title' => 'Все группы',
            'class' => 'btn btn-default'
        );
    }

    if(isset($group) && isset($groups) && $groups->count()) {
        $temp = array();
        $def_arr = array(
            'link' => action($module['class'] . '@getEdit', array('group_id' => $group->id)),
            'title' => $group->desc . ' (' . $group->count_users() . ')',
        );
        foreach ($groups as $grp) {
            $arr = array(
                'link' => action($module['class'] . '@getEdit', array('group_id' => $grp->id)),
                'title' => $grp->desc . ' (' . $grp->count_users() . ')',
            );
            if (@is_object($group) && $group->name == $grp->name)
                $def_arr = $arr;
            $temp[] = $arr;
        }
        /*
        if (!$def_arr)
            $def_arr = array(
                'link' => action($module['class'] . '@getIndex', null),
                'title' => 'Все запросы (' . UserRequest::count() . ')',
            );
        */
        $def_arr['class'] = 'btn btn-default';
        $def_arr['child'] = $temp;

        $menus[] = $def_arr;

        $menus[] = array(
            'link' => mb_substr(action('AdminUsersController@getIndex'), 0, -6) . "?group=" . $group->name,
            'title' => 'Участники',
            'class' => 'btn btn-info'
        );
    }


    if (Allow::action($module['group'], 'permissions')) {
        $menus[] = array(
            'link' => action($module['class'] . '@getCreate'),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }

?>

    <h1>{{ $page_title }}</h1>

    {{ Helper::drawmenu($menus) }}

