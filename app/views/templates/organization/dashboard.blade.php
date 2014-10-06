@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    @if(@$page['active_status']['status'] === FALSE)
    <div class="banner banner--red">
        @if(Session::get('message'))
        <span>{{ Session::get('message') }}</span>
        @else
        <span>{{ @$page['active_status']['message'] }}</span>
        <div>Для повторной отправки активационных данных нажмите на <a href="{{ URL::route('activation-repeated-sending-letter') }}">ссылку</a>.</div>
        @endif
    </div>
    @endif
    <?php
    $orders = Orders::whereUserId(Auth::user()->id)->orderBy('payment_status')->orderBy('created_at','DESC')->with('payment')->with(array('listeners'=>function($query){
        $query->with('listener');
        $query->with('course');
    }))->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
         <div class="employees">
            <h3>Сотрудники</h3>
            <div class="count-add">
                <?php $activeListenersIDs = array(); ?>
                <?php $allListenersIDs = array(); ?>
                @foreach($orders as $order)
                    @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
                        @foreach($order->listeners as $listener)
                            <?php $activeListenersIDs[$listener->user_id] = 1; ?>
                        @endforeach
                    @endif
                    @foreach($order->listeners as $listener)
                        <?php $allListenersIDs[$listener->user_id] = 1; ?>
                    @endforeach
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
                            <div class="count-add-num">{{ count($allListenersIDs) }}</div>
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
            <a href="{{ URL::route('page','catalog') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
            <h3>Заказы</h3>
            <ul class="orders-ul">
            <?php $showed = 0;?>
            @foreach($orders as $order)
                @if($showed >= 3)
                    <?php break; ?>
                @endif
                <li class="orders-li active-order">
                    <div class="orders-li-head">
                        <h4>Заказ №{{ $order->number }}</h4>
                        <div class="orders-status">
                            {{ $order->payment->title }}
                        </div>
                    </div>
                    <div class="orders-li-body">
                        @if($order->listeners->count())
                        <div class="orders-price">
                            <?php $price = 0.00; ?>
                            <?php $coursesIDs = array(); ?>
                            @foreach($order->listeners as $listener)
                                <?php $price += $listener->price; ?>
                                <?php $coursesIDs[$listener->course_id] = 1; ?>
                            @endforeach
                            <span class="start-price">{{ number_format($price,0,'.',' ')  }}.-</span> | <span class="end-price">{{ number_format($price,0,'.',' ')  }}.–</span>
                        </div>
                        @endif
                        <div class="orders-date">
                            Заказ создан:
                            <div>
                                {{ myDateTime::SwapDotDateWithTime($order->created_at) }}
                            </div>
                        </div>
                        <div class="orders-package">
                            <div>В заказе <a href="#">{{ count($coursesIDs) }} {{ Lang::choice('курс|курса|курсов',$order->listeners->count()); }}</a></div>
                            <div>для <a href="#">{{ $order->listeners->count() }} {{ Lang::choice('слушателя|слушателей|слушателей',$order->listeners->count()); }}</a></div>
                        </div>
                        @if(in_array($order->payment_status,array(2,3)) && in_array($order->close_status,array(0,1)))
                        <div class="orders-docs">
                            Посмотреть <a href="#">документы</a>
                        </div>
                        @endif
                    </div>
                <?php $showed++; ?>
            @endforeach
            </ul>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop