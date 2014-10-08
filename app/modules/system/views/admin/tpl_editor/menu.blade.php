<?
    $menus = array();

    if (Allow::action($module['group'], 'permissions')) {
        $menus[] = array(
            'link' => substr(URL::action($module['class'] . '@getIndex'), 0, -6 ),
            'title' => 'Все шаблоны',
            'class' => 'btn btn-default'
        );
    }

    if(isset($mod) && @$mod['title']) {
        $temp = array();
        /*
        $def_arr = array(
            #'link' => action($module['class'] . '@getEdit', array($mod_name)),
            'link' => '#',
            'title' => $mod['title'],
        );
        $def_arr['class'] = 'btn btn-default btn-disabled';
        $def_arr['child'] = $temp;
        $menus[] = $def_arr;
        */

        $menus[] = array(
            'link' => URL::action($module['class'].'@getEdit', array('mod' => $mod_name, 'tpl' => $file)),
            'title' => $mod['title'] . ': ' . $file,
            'class' => 'btn btn-info'
        );
    }
?>

    <h1>Редактор шаблонов</h1>

    {{ Helper::drawmenu($menus) }}

