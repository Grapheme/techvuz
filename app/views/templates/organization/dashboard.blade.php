@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php
    $orders = Orders::where('user_id',Auth::user()->id)->orderBy('payment_status')->orderBy('created_at','DESC')->with('payment')->with('listeners')->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
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
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <a href="{{ URL::route('signup-listener') }}" class="btn btn--bordered btn--blue pull-right js-btn-add-emp">
                                <span class="icon icon-slysh_dob"></span> Добавить
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Курсы</h3>
            <div class="count-add">
                <?php $activeCoursesIDs = array(); ?>
                <?php $closedCoursesIDs = array(); ?>
                @foreach($orders as $order)
                    @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
                        @foreach($order->listeners as $listener)
                            <?php $activeCoursesIDs[] = $listener->course_id; ?>
                        @endforeach
                    @endif
                    @if($order->close_status == 1)
                        @foreach($order->listeners as $listener)
                            <?php $closedCoursesIDs[] = $listener->course_id; ?>
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
            $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active',1)->with(array('study'=>function($query){
                $query->where('start_status',1);
                $query->where('over_status',0);
                $query->orderBy('start_date','DESC');
                $query->limit(3);
                $query->with('course');
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
                            <tr data-index="{{ $listener->id }}" {{ $index >= 1 ? 'class="hidden"' : '' }}>
                                <td>
                                    @if($index == 0)
                                    <a href="{{ URL::route('company-listener-profile',$listener->id) }}">{{ $listener->fio }}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ $study->course->code }}. {{ $study->course->title }}
                                    @if($index == 0 && $listener->study->count() > 1)
                                    <a href="javascript:void(0);" data-index="{{ $listener->id }}" class="more-courses">показать еще {{ $listener->study->count()-1 }} {{ Lang::choice('курс|курса|курсов',$listener->study->count()-1); }}</a>
                                    @endif
                                </td>
                                <td class="td-status-bar">
                                    <div class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress() }} clearfix">
                                        <div class="bar-part bar-part-1"></div>
                                        <div class="bar-part bar-part-2"></div>
                                        <div class="bar-part bar-part-3"></div>
                                    </div>
                                </td>
                            </tr>
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
@stop