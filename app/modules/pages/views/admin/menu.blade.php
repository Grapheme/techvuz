<?

    $menus = array();

    /*
    $menus[] = array(
        'link' => URL::route($module['entity'] . '.index', array()),
        'title' => 'Страницы',
        'class' => 'btn btn-default'
    );
    if (isset($element) && is_object($element) && $element->id) {
        $menus[] = array(
            'link' => URL::route($module['entity'] . '.edit', array($element->id)),
            'title' => $element->name ?: $element->slug,
            'class' => 'btn btn-default'
        );
    }
    */
    $menus[] = array(
        'link' => URL::route($module['entity'] . '.create', array()),
        'title' => 'Добавить',
        'class' => 'btn btn-primary'
    );

?>

    <h1 class="top-module-menu">
        <a href="{{ URL::route($module['entity'] . '.index') }}">Страницы</a>
        @if (isset($element) && is_object($element) && $element->id)
            &nbsp;&mdash;&nbsp;
            {{--<a href="{{ URL::route($module['entity'] . '.edit', array($element->id)) }}">--}}
                {{ $element->name ?: $element->slug }}
            {{--</a>--}}
        @endif
    </h1>

    {{ Helper::drawmenu($menus) }}
