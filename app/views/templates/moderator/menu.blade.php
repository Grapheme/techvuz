
@if(TRUE)

<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
@foreach(SystemModules::getSidebarModules() as $name => $module)
    <?php $menu_active = false; ?>
    @if ($module['link'] == (string)Request::segment(2) || $module['link'] == (string)Request::segment(2)."/".(string)Request::segment(3))
    <?php $menu_active = TRUE; ?>
    @endif
    <li class="cabinet-menu-li">
        <a href="{{ URL::to(link::auth($module['link'])) }}" {{ $menu_active ? 'class="active"' : '' }}>
            <i class="fa fa-lg fa-fw {{ $module['class'] }}">
                @if (@is_callable($module['icon_badge']))
                    {{ $module['icon_badge']() }}
                @endif
            </i>
            <span class="menu-item-parent">{{{ $module['title'] }}}</span>
        </a>
    </li>
@endforeach
</ul>

@else

<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
        <a href="{{ URL::route('moderator-companies-list') }}">Компании</a>
    </li>
    <li class="cabinet-menu-li">
        <a href="{{ URL::route('moderator-orders-list') }}">Заказы</a>
    </li>
    <li class="cabinet-menu-li">
        <a href="{{ URL::route('moderator-listeners-list') }}">Слушатели</a>
    </li>
</ul>

@endif