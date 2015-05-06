<?
#Helper:dd($dic_id);
$menus = array();
/*
$menus[] = array(
    'link' => URL::route(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)),
    'title' => $dic->name,
    'class' => 'btn btn-default'
);
*/
if (isset($element) && is_object($element) && $element->name && 0) {
    $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, $element->id)), 'title' => "&laquo;" . $element->name . "&raquo;", 'class' => 'btn btn-default'
    );
}
if (Allow::action($module['group'], 'dicval_delete') && isset($element) && is_object($element) && $element->id
    && (!isset($dic_settings['min_elements'])
        || ($dic_settings['min_elements'] > 0 && $total_elements > $dic_settings['min_elements']))
) {
    $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.destroy' : 'entity.destroy', array('dic_id' => $dic_id, $element->id)), #'link' => '#',
            'title' => '<i class="fa fa-trash-o"></i>', 'class' => 'btn btn-danger remove-dicval-record', 'others' => [
                #'data-dicval_id' => $element->id,
                'data-goto' => action(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)), 'title' => 'Удалить запись'
            ]
    );
}
if (Allow::action($module['group'], 'dicval_create')
    && (!isset($dic_settings['max_elements']) || !$dic_settings['max_elements'] || (isset($total_elements_current_selection) && $dic_settings['max_elements'] > $total_elements_current_selection))
) {
    $current_link_attributes = Helper::multiArrayToAttributes(Input::get('filter'), 'filter');
    $menus[] = array(
            'link' => action(is_numeric($dic_id) ? 'dicval.create' : 'entity.create', array('dic_id' => $dic_id) + $current_link_attributes), 'title' => 'Добавить', 'class' => 'btn btn-primary'
    );
}
if (Allow::action($module['group'], 'import')) {
    $menus[] = array(
            'link' => action('dic.import', array('dic_id' => $dic_id)), 'title' => 'Импорт', 'class' => 'btn btn-primary'
    );
}
if (Allow::action($module['group'], 'edit') && (!$dic->entity || Allow::superuser())) {
    $menus[] = array(
            'link' => action('dic.edit', array('dic_id' => $dic->id)), 'title' => 'Изменить', 'class' => 'btn btn-success'
    );
}

if (isset($dic_settings['menus']))
    $dic_menu = $dic_settings['menus'];
#Helper::d($dic_menu);
if (isset($dic_menu) && is_callable($dic_menu)) {
    $tmp = (array)$dic_menu($dic, isset($element) && is_object($element) ? $element : NULL);
    $menus = array_merge($menus, $tmp);
}

#Helper::d($menus);
?>

<? $url = URL::route(is_numeric($dic_id) ? 'dicval.index' : 'entity.index',  array('dic_id' => $dic_id)); ?>
{{ Form::open(array('url' => $url, 'class' => 'smart-form navbar-form navbar-right', 'id' => 'search-form', 'role' => 'search', 'method' => 'GET', 'style' => 'position:relative; top:32px; z-index:9999;')) }}
    {{ Form::text('q', Input::get('q'), array('placeholder' => 'Поиск', 'class' => 'form-control', 'style' => 'padding: 0 5px;')) }}
    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
{{ Form::close() }}

<h1 class="top-module-menu">
    <a href="{{ URL::route(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)) }}">{{ $dic->name }}</a>
    {{ $dic->entity && is_numeric($dic_id) ? ' <i class="fa fa-angle-double-right"></i> <a href="' . URL::route('entity.index', $dic->slug) . '" title="Вынесено в отдельную сущность">' . $dic->slug . '</a>' : '' }}
    @if (isset($element) && is_object($element) && $element->name)
        &nbsp;&mdash;&nbsp;
        {{ $element->name }}
    @elseif (isset($search_query))
        &nbsp;&mdash;&nbsp;
        Поиск: &laquo;{{ $search_query }}&raquo;
    @endif
</h1>

{{ Helper::drawmenu($menus) }}
