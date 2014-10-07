@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $orders = Orders::whereUserId(Auth::user()->id)->orderBy('payment_status')->orderBy('created_at','DESC')->with('payment')->with(array('listeners'=>function($query){
        $query->with('listener');
        $query->with('course');
    }))->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <a href="{{ URL::route('page','catalog') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
            <h3>Заказы</h3>
            <div class="tabs usual-tabs">
                <ul>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            @if($order->payment_status == 1 && $order->close_status == 0)
                                <?php $count_orders++ ; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-11">Новые <span class="filter-count">{{ $count_orders ? $count_orders : '' }}</span></a>
                    </li>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
                                <?php $count_orders++ ; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-12">Активные <span class="filter-count">{{ $count_orders ? $count_orders : '' }}</span></a>
                    </li>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            @if($order->close_status == 1)
                                <?php $count_orders++ ; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-13">Завершенные <span class="filter-count">{{ $count_orders ? $count_orders : '' }}</span></a>
                    </li>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            <?php $count_orders++ ; ?>
                        @endforeach
                        <a href="#tabs-14">Все <span class="filter-count">{{ $count_orders ? $count_orders : '' }}</span></a>
                    </li>
                </ul>
                <div id="tabs-11">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        @if($order->payment_status == 1 && $order->close_status == 0)
                        <li class="orders-li new-order">
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
                            </div>
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-12">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
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
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-13">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        @if($order->close_status == 1)
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>Заказ №{{ $order->number }}</h4>
                                <div class="orders-status">
                                    Завершен
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
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-14">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        <li class="orders-li {{ ($order->payment_status == 1 && $order->close_status == 0) ? 'new-order' : 'active-order' }}">
                            <div class="orders-li-head">
                                <h4>Заказ №{{ $order->number }}</h4>
                                <div class="orders-status">
                                    {{ $order->close_status == 0 ? $order->payment->title : 'Завершен' }}
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
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop