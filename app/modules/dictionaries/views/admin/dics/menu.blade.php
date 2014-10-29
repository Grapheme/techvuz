<?
    $menus = array();
    $menus[] = array(
        'link' => URL::route('dic.index', null),
        'title' => 'Словари',
        'class' => 'btn btn-default'
    );
    if (@is_object($element) && $element->id) {
        $menus[] = array(
            'link' => URL::route('dic.edit', $element->id),
            'title' => '&laquo;' . $element->name . '&raquo;',
            'class' => 'btn btn-default'
        );
        $menus[] = array(
            'link' => $element->entity
                    ? URL::route('entity.index', $element->slug)
                    : URL::route('dicval.index', $element->id)
            ,
            'title' => 'Содержимое (' . $element->values_count() . ')',
            'class' => 'btn btn-warning'
        );
    }
    if (Allow::action($module['group'], 'create')) {
        $menus[] = array(
            'link' => URL::route('dic.create', null),
            'title' => 'Добавить',
            'class' => 'btn btn-primary'
        );
    }
    if (Allow::action($module['group'], 'import') && @$dic_id) {
        $menus[] = array(
            'link' => action('dic.import', array('dic_id' => $dic_id)),
            'title' => 'Импорт',
            'class' => 'btn btn-primary'
        );
    }
    if (Allow::superuser() && @is_object($element) && $element->id) {
        $menus[] = array(
            'link' => action('dic.sphinx', array('dic_id' => $element->id)),
            'title' => '<i class="fa fa-search-plus"></i>',
            'class' => 'btn btn-info',
            'others' => array(
                'title' => 'SQL-запрос для Sphinx',
                'target' => '_blank',
            ),
        );
    }
?>
    
    <h1>Словари</h1>

    {{ Helper::drawmenu($menus) }}

