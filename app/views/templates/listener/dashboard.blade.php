@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php
        $courses = OrderListeners::where('user_id',Auth::user()->id)
            ->orderBy('start_date','DESC')
            ->orderBy('access_status','DESC')
            ->orderBy('updated_at','DESC')
            ->with('course')
            ->with('final_test')
            ->get();
    ?>
    <h2>{{ User_listener::where('id',Auth::user()->id)->pluck('fio') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
            <h3>Курсы</h3>
            <div class="count-add">
                <?php $activeCoursesCount = 0; ?>
                <?php $blockedCoursesCount = 0; ?>
                <?php $closedCoursesCount = 0; ?>
                @foreach($courses as $course)
                    @if($course->access_status == 1 && $course->over_status == 0)
                    <?php $activeCoursesCount++; ?>
                    @endif
                    @if($course->access_status == 0)
                    <?php $blockedCoursesCount++; ?>
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
                            <div class="count-add-sign">Не доступно</div>
                            <div class="count-add-num">{{ $blockedCoursesCount }}</div>
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
        <div>
            <h3>Уведомления</h3>
            <div class="notifications">
                <div class="notifications-nav">
                    <span class="icon icon-angle-left js-notif-left"></span>
                    <span class="notifications-count">
                        <span class="current">1</span> / <span class="all"></span>
                    </span>
                    <span class="icon icon-angle-right js-notif-right"></span>
                </div>
                <ul class="notifications-ul">
                @for($i=0;$i<19;$i++)
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение {{ $i+1 }}
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                @endfor
                </ul>
            </div>
        </div>
        <div>
        <?php $show_block = FALSE; ?>
        @foreach($courses as $listener_course)
            @if($listener_course->access_status == 1 && $listener_course->start_status == 1 && $listener_course->over_status == 0)
                <?php $show_block = TRUE; break;?>
            @endif
        @endforeach
        @if($show_block)
            <h3>Ход обучения</h3>
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    </tr>
                @foreach($courses as $listener_course)
                    @if($listener_course->access_status == 1 && $listener_course->start_status == 1 && $listener_course->over_status == 0)
                        @include(Helper::acclayout('assets.course-tr'))
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop