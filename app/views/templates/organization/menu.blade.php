<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-orders') }} {{ Helper::isRoute('organization-order') }} href="{{ URL::route('organization-orders') }}"><i class="fa fa-lg fa-fw fa-list-ul"></i> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-listeners') }} {{ Helper::isRoute('organization-listener-profile') }} {{ Helper::isRoute('organization-listener-profile-edit') }} href="{{ URL::route('organization-listeners') }}"><i class="fa fa-lg fa-fw fa-users"></i> Сотрудники</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-study') }} href="{{ URL::route('organization-study') }}"><i class="fa fa-lg fa-fw fa-graduation-cap"></i> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-notifications') }} href="{{ URL::route('organization-notifications') }}"><i class="fa fa-lg fa-fw fa-envelope-o"></i> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-profile') }} {{ Helper::isRoute('organization-profile-edit') }} href="{{ URL::route('organization-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>