<?
    $menus = array();

    $menus[] = array(
        'link' => URL::route($module['name'] . '.index'),
        'title' => 'Все меню',
        'class' => 'btn btn-default'
    );

    if (isset($element) && $element->id) {
        $menus[] = array(
            'link' => URL::route($module['name'] . '.edit', $element->id),
            'title' => $element->title ?: $element->name,
            'class' => 'btn btn-default'
        );

        $menus[] = array(
            'link' => URL::route($module['name'] . '.manage', $element->id),
            'title' => 'Управление',
            'class' => 'btn btn-default'
        );
    }

    $menus[] = array(
        'link' => URL::route($module['name'] . '.create'),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
    );
?>

    <h1>Конструктор меню</h1>

    {{ Helper::drawmenu($menus) }}

