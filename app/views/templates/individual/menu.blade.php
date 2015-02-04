<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-orders') }} href="{{ URL::route('individual-orders') }}"><span class="icon icon-zakaz"></span> Заказы</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-study') }} href="{{ URL::route('individual-study') }}"><span class="icon icon-obych"></span> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-notifications') }} href="{{ URL::route('individual-notifications') }}"><span class="icon icon-yved"></span> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('individual-profile') }} {{ Helper::isRoute('individual-profile-edit') }} href="{{ URL::route('individual-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>