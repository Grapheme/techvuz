<?
    #Helper:dd($dic_id);
    $menus = array();
    $menus[] = array(
        'link' => action(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)),
        'title' => $dic->name,
        'class' => 'btn btn-default'
    );
    if (@is_object($element) && $element->name) {
        $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, $element->id)),
            'title' => "&laquo;" . $element->name . "&raquo;",
            'class' => 'btn btn-default'
        );
    }
    $menus[] = array(
        'link' => action(is_numeric($dic_id) ? 'dicval.create' : 'entity.create', array('dic_id' => $dic_id)),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
    );
    if (Allow::action($module['group'], 'edit') && (!$dic->entity || Allow::superuser())) {
        $menus[] = array(
            'link' => action('dic.edit', array('dic_id' => $dic->id)),
            'title' => 'Изменить',
            'class' => 'btn btn-success'
        );
    }
?>
    
    <h1>{{ $dic->name }}{{ $dic->entity && is_numeric($dic_id) ? ' <i class="fa fa-angle-double-right"></i> <a href="' . URL::route('entity.index', $dic->slug) . '" title="Вынесено в отдельную сущность">' . $dic->slug . '</a>' : '' }}</h1>

    {{ Helper::drawmenu($menus) }}
