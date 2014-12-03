@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php
    $orders = Orders::where('user_id',Auth::user()->id)->where('archived',FALSE)->orderBy('created_at','DESC')->with('payment','payment_numbers')->with('listeners')->get();
    $dashboardNotificationBlockTargetValue = Config::get('site.user_setting.dashboard-target-notification-block') ? Config::get('site.user_setting.dashboard-target-notification-block') : 0;
    $messages = Dictionary::valuesBySlug('system-messages',function($query) use ($dashboardNotificationBlockTargetValue){
        $setDate = Config::get('site.user_setting.dashboard-target-notification-block-date') ? Config::get('site.user_setting.dashboard-target-notification-block-date') : \Carbon\Carbon::now()->subMonth() ;
        $query->orderBy('dictionary_values.updated_at','DESC');
        $query->orderBy('dictionary_values.id','DESC');
        $query->where('dictionary_values.updated_at','>=',$setDate);
        $query->filter_by_field('user_id',Auth::user()->id);
    });
    if($messages->count()):
        (new AccountsOperationController())->saveUserSetting('dashboard-target-notification-block',0,FALSE);
        $dashboardNotificationBlockTargetValue = 1;
    endif;
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
            <a href="{{ URL::route('signup-listener') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right js-btn-add-emp">
                <span class="icon icon-slysh_dob"></span> Добавить
            </a>
            <h3>Сотрудники</h3>
            <div class="count-add">
                <?php $activeListenersIDs = array(); ?>
                @foreach($orders as $order)
                    @if($order->close_status == 0)
                        @foreach($order->listeners as $listener)
                            @if($listener->start_status == 1)
                            <?php $activeListenersIDs[$listener->user_id] = 1; ?>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="count-add-sign">Обучается</div>
                            <div class="count-add-num">{{ count($activeListenersIDs) }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="count-add-sign">Всего</div>
                            <div class="count-add-num">{{ Listener::where('organization_id',Auth::user()->id)->count() }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter no-padding">
                            
                        </div>
                    </div>
                </div>
            </div>
            <h3>Курсы</h3>
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
        <div {{ $dashboardNotificationBlockTargetValue ? '' : 'class="hidden"' }}>
            <h3>Уведомления</h3>
            <div class="notifications">
                <div class="notifications-nav">
                    <span class="icon icon-angle-left js-notif-left"></span>
                    <span class="notifications-count">
                        <span class="current">1</span> / <span class="all"></span>
                    </span>
                    <span class="icon icon-angle-right js-notif-right">
                        <a href="{{ URL::route('organization-notifications') }}" class="all-notifications">
                            Полный список
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
                                    Системное сообщение ololo{{ $index+1 }}
                                </div>
                                <div class="notif-cont">
                                    {{ $message->name }}
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    {{ $message->updated_at->timezone('Europe/Moscow')->format('d.m.Y в H:i') }}
                                </div>
                                <div class="notif-delete js-notif-delete">
                                {{ Form::open(array('url'=>URL::route('organization-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                                    
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
            <h3>Заказы</h3>
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
            $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active','>=',1)->with(array('study'=>function($query){
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
            foreach($listeners as $listener):
                if($listener->study->count()):
                    $hasStudyProgress = TRUE;
                    break;
                endif;
            endforeach;
        ?>
            <h3>Ход обучения</h3>
        @if($hasStudyProgress)
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                @foreach($listeners as $listener)
                    @if($listener->study->count())
                        @foreach($listener->study as $index => $study)
                            @include(Helper::acclayout('assets.listener-course-tr'))
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <div>
                <span>На данный момент никто не обучается</span>
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
{{ HTML::script('theme/js/organization.js') }}
@stop