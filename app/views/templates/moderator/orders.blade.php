@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
 <?php
    $orders = Orders::orderBy('payment_status')->orderBy('created_at','DESC')->with('payment')->with(array('listeners'=>function($query){
        $query->with('listener');
        $query->with('course');
    }))->get();
?>
<h3>Список заказов</h3>
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
                @include(Helper::acclayout('assets.order'))
            @endif
        @endforeach
        </ul>
    </div>
    <div id="tabs-12">
        <ul class="orders-ul">
        @foreach($orders as $order)
            @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
                @include(Helper::acclayout('assets.order'))
            @endif
        @endforeach
        </ul>
    </div>
    <div id="tabs-13">
        <ul class="orders-ul">
        @foreach($orders as $order)
            @if($order->close_status == 1)
                @include(Helper::acclayout('assets.order'))
            @endif
        @endforeach
        </ul>
    </div>
    <div id="tabs-14">
        <ul class="orders-ul">
        @foreach($orders as $order)
            @include(Helper::acclayout('assets.order'))
        @endforeach
        </ul>
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop