@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet" xmlns="http://www.w3.org/1999/html">
    <?php
        $courses = OrderListeners::where('user_id',Auth::user()->id)
            ->orderBy('start_date','DESC')
            ->orderBy('access_status','DESC')
            ->orderBy('updated_at','DESC')
            ->with('course')
            ->with('final_test')
            ->get();
        $messages = Dictionary::valuesBySlug('system-messages',function($query){
            $lastMonth = \Carbon\Carbon::now()->subMonth();
            $query->orderBy('dictionary_values.updated_at','DESC');
            $query->where('dictionary_values.updated_at','>=',$lastMonth);
            $query->filter_by_field('user_id','=',Auth::user()->id);
       });
    ?>
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <a class="name-dashboard" href="{{ URL::route('dashboard') }}"><h1>{{ $account->fio }}</h1></a>
    <div class="margin-bottom-20">
        <a class="icon--blue" href="{{ URL::route('listener-profile') }}">Профиль</a>
    </div>  
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
            <h3 class="no-margin">Курсы</h3>
            <div class="count-add">
                <?php $activeCoursesCount = 0; ?>
                <?php $closedCoursesCount = 0; ?>
                @foreach($courses as $course)
                    @if($course->access_status == 1 && $course->over_status == 0)
                    <?php $activeCoursesCount++; ?>
                    @endif
                    @if($course->access_status == 1 && $course->over_status == 1)
                    <?php $closedCoursesCount++; ?>
                    @endif
                @endforeach
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="count-add-sign">Доступно</div>
                            <div class="count-add-num">{{ $activeCoursesCount }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="count-add-sign">Завершено</div>
                            <div class="count-add-num">{{ $closedCoursesCount }}</div>
                            <div class="count-add-dots"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($messages->count())
            <?php
                $dashboardNotificationBlockTargetValue = Config::get('site.user_setting.dashboard-target-notification-block') ? 0 : 1;
            ?>
            <div {{ $dashboardNotificationBlockTargetValue == 1 ? '' : 'class="hidden"' }}>
                <h3>Уведомления</h3>
                <div class="notifications">
                    <div class="notifications-nav">
                        <span class="icon icon-angle-left js-notif-left"></span>
                        
                        <span class="notifications-count">
                            <span class="current">1</span> / <span class="all"></span>
                        </span>

                        <span class="icon icon-angle-right js-notif-right"></span>

                        <a href="{{ URL::route('listener-notifications') }}" class="all-notifications">
                            Уведомления
                        </a>

                        <span>
                            <a data-action="{{ URL::route('setting-update',array('setting_slug'=>'dashboard-target-notification-block','value'=>$dashboardNotificationBlockTargetValue)) }}" class="white-link pull-right js-close-notifications">закрыть</a>
                        </span>
                    </div>
                    <ul class="notifications-ul">
                    @foreach($messages as $index => $message)
                        <li class="notifications-li container-fluid">
                            <div class="row">
                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                    <!-- <div class="notif-type">
                                        Системное сообщение {{ $index+1 }}
                                    </div> -->
                                    <div class="notif-cont">
                                        {{ $message->name }}
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <div class="notif-date font-sm">
                                        {{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}
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
        <?php $show_block = FALSE; ?>
        @foreach($courses as $listener_course)
            @if($listener_course->access_status == 1 && $listener_course->start_status == 1 && $listener_course->over_status == 0)
                <?php $show_block = TRUE; break;?>
            @endif
        @endforeach
            <h3>Ход обучения</h3>
        @if($show_block)
            <table class="tech-table sortable">
                <thead>
                    <tr>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($courses as $listener_course)
                    @if($listener_course->access_status == 1 && $listener_course->start_status == 1 && $listener_course->over_status == 0)
                        @include(Helper::acclayout('assets.course-tr'))
                    @endif
                @endforeach
                </tbody>
            </table>
        @else
            <p>Отсутствуют активные изучаемые курсы.<br>Что бы просмотреть доступные курсы перейдите в раздел <a href="{{ URL::route('listener-study') }}">Обучение</a></p>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop