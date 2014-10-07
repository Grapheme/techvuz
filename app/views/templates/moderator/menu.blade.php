<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
@foreach(SystemModules::getSidebarModules() as $name => $module)
    <?php $menu_active = false; ?>
    @if ($module['link'] == (string)Request::segment(2) || $module['link'] == (string)Request::segment(2)."/".(string)Request::segment(3))
    <?php $menu_active = TRUE; ?>
    @endif
    <li class="cabinet-menu-li">
    	<a {{ $menu_active ? 'class="active"' : '' }} href="{{ URL::route('company-orders') }}"><span class="icon icon-zakaz"></span> Заказы</a>
    </li>
@endforeach
</ul>