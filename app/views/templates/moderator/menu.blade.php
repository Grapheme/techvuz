<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('orders','order')) ? 'class="active"' : '' }} href="{{ URL::route('moderator-orders-list') }}"><i class="fa fa-lg fa-fw fa-bookmark"></i> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('notifications')) ? 'class="active"' : '' }} href="{{ URL::route('moderator-notifications') }}"><i class="fa fa-lg fa-fw fa-envelope-o"></i> Уведомления</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(3),array('information-baners')) ? 'class="active"' : '' }} href="{{ link::auth('entity/information-baners') }}"><span class="fa fa-lg fa-fw fa-info"></span> Инф.банеры</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('pages')) ? 'class="active"' : '' }} href="{{ link::auth('pages') }}"><span class="fa fa-lg fa-fw fa-list-alt"></span> Страницы</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(3),array('licenses-certificates')) ? 'class="active"' : '' }} href="{{ link::auth('entity/licenses-certificates') }}"><span class="fa fa-lg fa-fw fa-picture-o"></span> Лицензии</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('companies')) ? 'class="active"' : '' }} href="{{ URL::route('moderator-companies-list') }}"><i class="fa fa-lg fa-fw fa-building"></i> Компании</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('education')) ? 'class="active"' : '' }} href="{{ link::auth('education/directions') }}"><i class="fa fa-lg fa-fw fa-book"></i> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('news')) ? 'class="active"' : '' }} href="{{ link::auth('news') }}"><span class="fa fa-lg fa-fw fa-calendar"></span> Новости</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(3),array('order-documents')) ? 'class="active"' : '' }} href="{{ link::auth('entity/order-documents') }}"><span class="fa fa-lg fa-fw fa-clipboard"></span> Документы</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(3),array('reviews')) ? 'class="active"' : '' }} href="{{ link::auth('entity/reviews') }}"><span class="fa fa-lg fa-fw fa-comments-o"></span> Отзывы</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('listeners')) ? 'class="active"' : '' }} href="{{ URL::route('moderator-listeners-list') }}"><i class="fa fa-lg fa-fw fa-group"></i> Слушатели</a>
    </li>
    <li class="cabinet-menu-li">
        <a {{ in_array(Request::segment(2),array('statistic')) ? 'class="active"' : '' }} href="{{ URL::route('moderator-statistic') }}"><i class="fa fa-lg fa-fw fa-bar-chart"></i> Статистика</a>
    </li>
</ul>

{{--@foreach(SystemModules::getSidebarModules() as $name => $module)--}}
    <?php #$modules_menu[] = $module;?>
    <?php #$menu_active = false; ?>
    {{--@if ($module['link'] == (string)Request::segment(2) || $module['link'] == (string)Request::segment(2)."/".(string)Request::segment(3))--}}
        <?php #$menu_active = TRUE; ?>
    {{--@endif--}}
    {{--<li class="cabinet-menu-li">--}}
        {{--<a href="{{ URL::to(link::auth($module['link'])) }}" {{ $menu_active ? 'class="active"' : '' }}>--}}
            {{--<i class="fa fa-lg fa-fw {{ $module['class'] }}">--}}
                {{--@if (@is_callable($module['icon_badge']))--}}
                    {{--{{ $module['icon_badge']() }}--}}
                {{--@endif--}}
            {{--</i>--}}
            {{--<span class="menu-item-parent">{{{ $module['title'] }}}</span>--}}
        {{--</a>--}}
    {{--</li>--}}
{{--@endforeach--}}