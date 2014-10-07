<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('company-orders') }} href="{{ URL::route('company-orders') }}"><span class="icon icon-zakaz"></span> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('company-listeners') }} href="{{ URL::route('company-listeners') }}"><span class="icon icon-slysh"></span> Сотрудники</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('company-study') }} href="{{ URL::route('company-study') }}"><span class="icon icon-obych"></span> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('company-notifications') }} href="{{ URL::route('company-notifications') }}"><span class="icon icon-yved"></span> Уведомления</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('company-profile') }} href="{{ URL::route('company-profile') }}"><span class=""></span> Профиль</a>
    </li>
</ul>