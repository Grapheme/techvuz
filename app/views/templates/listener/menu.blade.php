<ul class="cabinet-menu-ul clearfix margin-top-20 margin-bottom-40">
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-study') }} {{ Helper::isRoute('listener-study-course') }} {{ Helper::isRoute('listener-study-testing') }} {{ Helper::isRoute('listener-study-test-result') }} href="{{ URL::route('listener-study') }}"><i class="fa fa-lg fa-fw fa-graduation-cap"></i> Обучение</a>
    </li>
    <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-notifications') }} href="{{ URL::route('listener-notifications') }}"><i class="fa fa-lg fa-fw fa-envelope-o"></i> Уведомления</a>
    </li>
    <!-- <li class="cabinet-menu-li">
    	<a {{ Helper::isRoute('listener-profile') }} {{ Helper::isRoute('listener-profile-edit') }} href="{{ URL::route('listener-profile') }}"><span class=""></span> Профиль</a>
    </li> -->
</ul>