<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-orders') }} {{ Helper::isRoute('organization-order') }} href="{{ URL::route('organization-orders') }}"><span class="icon icon-zakaz"></span> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-listeners') }} {{ Helper::isRoute('organization-listener-profile') }} {{ Helper::isRoute('organization-listener-profile-edit') }} href="{{ URL::route('organization-listeners') }}"><span class="icon icon-slysh"></span> Сотрудники</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-study') }} href="{{ URL::route('organization-study') }}"><span class="icon icon-obych"></span> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-notifications') }} href="{{ URL::route('organization-notifications') }}"><span class="icon icon-yved"></span> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('organization-profile') }} {{ Helper::isRoute('organization-profile-edit') }} href="{{ URL::route('organization-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>