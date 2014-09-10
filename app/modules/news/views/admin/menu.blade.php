<?
    $page_title = "Новости";


    $menus = array();

    if (Allow::action($module['group'], 'view')) {
        $menus[] = array(
            'link' => action($module['entity'].'.index'),
            'title' => 'Все новости',
            'class' => 'btn btn-default'
        );
    }

    if (Allow::action($module['group'], 'create')) {
        $menus[] = array(
            'link' => action($module['entity'].'.create'),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }

?>

    <h1>{{ $page_title }}</h1>

    {{ Helper::drawmenu($menus) }}

