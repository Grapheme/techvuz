<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-orders') }} href="{{ URL::route('individual-orders') }}"><i class="fa fa-lg fa-fw fa-list-ul"></i> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-study') }} href="{{ URL::route('individual-study') }}"><i class="fa fa-lg fa-fw fa-graduation-cap"></i> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-notifications') }} href="{{ URL::route('individual-notifications') }}"><i class="fa fa-lg fa-fw fa-envelope-o"></i> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-profile') }} {{ Helper::isRoute('individual-profile-edit') }} href="{{ URL::route('individual-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>