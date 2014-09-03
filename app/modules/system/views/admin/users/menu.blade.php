<?
    $page_title = "Пользователи";


    $menus = array();

    /*
    if (Allow::action($module['group'], 'permissions')) {
        $menus[] = array(
            'link' => mb_substr(action($module['class'] . '@getIndex'), 0, -6),
            'title' => 'Все пользователи',
            'class' => 'btn btn-default'
        );
    }
    */

    $temp = array();
    $def_arr = array(
        'link' => mb_substr(action($module['class'] . '@getIndex'), 0, -6),
        'title' => 'Все пользователи (' . User::count() . ')',
    );
    $temp[] = $def_arr;

    if(isset($groups) && $groups->count()) {
        foreach ($groups as $grp) {
            $arr = array(
                'link' => mb_substr(action($module['class'] . '@getIndex'), 0, -6) . "?group_id=" . $grp->id,
                'title' => $grp->desc . ' (' . $grp->count_users() . ')',
            );
            if (@is_object($group) && $group->name == $grp->name)
                $def_arr = $arr;
            $temp[] = $arr;
        }

    }

    $def_arr['class'] = 'btn btn-default';
    if (count($temp) > 1)
        $def_arr['child'] = $temp;
    $menus[] = $def_arr;


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

