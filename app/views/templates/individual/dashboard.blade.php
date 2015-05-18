@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php
    $orders = Orders::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->with('payment','payment_numbers')->get();
    $dashboardNotificationBlockTargetValue = Config::get('site.user_setting.dashboard-target-notification-block') ? Config::get('site.user_setting.dashboard-target-notification-block') : 0;
    $messages = Dictionary::valuesBySlug('system-messages',function($query) use ($dashboardNotificationBlockTargetValue){
        $setDate = Config::get('site.user_setting.dashboard-target-notification-block-date') ? Config::get('site.user_setting.dashboard-target-notification-block-date') : \Carbon\Carbon::now()->subMonth() ;
        $query->orderBy('dictionary_values.updated_at','DESC');
        $query->orderBy('dictionary_values.id','DESC');
        $query->where('dictionary_values.updated_at','>=',$setDate);
        $query->filter_by_field('user_id','=',Auth::user()->id);
    });
    if($messages->count()):
        (new AccountsOperationController())->saveUserSetting('dashboard-target-notification-block',0,FALSE);
        $dashboardNotificationBlockTargetValue = 1;
    endif;
    ?>
    <h2>{{ User_individual::where('id',Auth::user()->id)->pluck('fio') }}</h2>
    <div class="margin-bottom-20">
        <a class="icon--blue" href="{{ URL::route('individual-profile') }}">Профиль</a>
    </div> 
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
            <h3 class="no-margin">Курсы</h3>
            <div class="count-add">
                <?php $activeCoursesIDs = array(); ?>
                <?php $closedCoursesIDs = array(); ?>
                @foreach($orders as $order)
                    @if(in_array($order->payment_status,array(2,3,4,5)) && $order->close_status == 0)
                        @foreach($order->listeners as $listener)
                            @if($listener->start_status > 0)
                            <?php $activeCoursesIDs[$listener->course_id] = 1; ?>
                            @endif
                        @endforeach
                    @endif
                    @if($order->close_status == 1)
                        @foreach($order->listeners as $listener)
                            <?php $closedCoursesIDs[$listener->course_id] = 1; ?>
                        @endforeach
                    @endif
                @endforeach
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="count-add-sign">Активно</div>
                            <div class="count-add-num">{{ count($activeCoursesIDs) }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="count-add-sign">Завершено</div>
                            <div class="count-add-num">{{ count($closedCoursesIDs) }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($messages->count())
        <div {{ $dashboardNotificationBlockTargetValue ? '' : 'class="hidden"' }} id="orgNotifications">
            <h3><a href="{{ URL::route('individual-notifications') }}">Уведомления</a></h3>
            <div class="notifications">
                <div class="notifications-nav">
                    <span class="icon icon-angle-left js-notif-left"></span>
                    <span class="notifications-count">
                        <span class="current">1</span> / <span class="all"></span>
                    </span>
                    <span class="icon icon-angle-right js-notif-right">
                        <a href="{{ URL::route('individual-notifications') }}" class="all-notifications">
                            Уведомления
                        </a>
                    </span>
                    <span>
                        <a data-action="{{ URL::route('setting-update',array('setting_slug'=>'dashboard-target-notification-block','value'=>$dashboardNotificationBlockTargetValue)) }}" class="white-link pull-right js-close-notifications">закрыть</a>
                    </span>
                </div>
                <ul class="notifications-ul">
                @foreach($messages as $index => $message)
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение {{ $index+1 }}
                                </div>
                                <div class="notif-cont">
                                    {{ $message->name }}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    {{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}
                                </div>
                                <div class="notif-delete js-notif-delete">
                                {{ Form::open(array('url'=>URL::route('individual-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                                    <button type="submit" class="icon-bag-btn" title="Удалить"></button>
                                {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
        @endif
        <div>
            <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
            <h3><a href="{{ URL::route('individual-orders') }}">Заказы</a></h3>
            <ul class="orders-ul">
            <?php $showed = 0; $maxCourses = 3; ?>
            @if(hasCookieData('ordering'))
                <?php $maxCourses = 2; ?>
                @include(Helper::acclayout('assets.temp-order'))
            @endif
            @foreach($orders as $order)
                @if($showed >= $maxCourses)
                    <?php break; ?>
                @endif
                @include(Helper::acclayout('assets.order'))
                <?php $showed++; ?>
            @endforeach
            </ul>
        </div>
        <div>
        <?php
            $courses = User_individual::where('id',Auth::user()->id)->where('active','>=',1)->with(array('study'=>function($query){
                $query->where('start_status',1);
                $query->where('over_status',0);
                $query->orderBy('start_status','DESC');
                $query->orderBy('access_status','DESC');
                $query->orderBy('start_date','DESC');
                $query->limit(3);
                $query->with('course');
                $query->with('final_test');
            }))->get();
            $hasStudyProgress = FALSE;
            foreach($courses as $course):
                if($course->study->count()):
                    $hasStudyProgress = TRUE;
                    break;
                endif;
            endforeach;
        ?>
            <h3><a href="{{ URL::route('individual-study') }}">Ход обучения</a></h3>
        @if($hasStudyProgress)
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>                   
                @foreach($courses as $course)
                    @if($course->study->count())
                        @foreach($course->study as $index => $listener_course)
                            @include(Helper::acclayout('assets.course-tr'))
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <div>
                <span>На данный момент Вы не обучаетесь</span>
            </div>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('js/vendor/SmartNotification.min.js') }}
{{ HTML::script('js/system/messages.js') }}
{{ HTML::script('theme/js/individual.js') }}
<script>
    $(function(){
        $(document).on('click', '.hide-courses', function(e){
            e.preventDefault();
            var index = $(this).parents('tr').data('index');
            var trs = $("tr[data-index='"+index+"']").not("tr[data-index='"+index+"']:first").show().slideUp(500).addClass('hidden');
            $(this).hide();
            $("tr[data-index='"+index+"']:first").find('.more-courses').show();
        });
    });
</script>
@stop