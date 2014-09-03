<?

    $menus = array();
    $menus[] = array(
        'link' => URL::route($module['entity'] . '.index', array()),
        'title' => 'Страницы',
        'class' => 'btn btn-default'
    );
    $menus[] = array(
        'link' => URL::route($module['entity'] . '.create', array()),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
    );

?>

    <h1>Страницы</h1>

    {{ Helper::drawmenu($menus) }}
