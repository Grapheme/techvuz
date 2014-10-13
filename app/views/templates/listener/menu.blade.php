<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-study') }} href="{{ URL::route('listener-study') }}"><span class="icon icon-obych"></span> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-notifications') }} href="{{ URL::route('listener-notifications') }}"><span class="icon icon-yved"></span> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-profile') }} href="{{ URL::route('listener-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>