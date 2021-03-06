@if(isset($order) && is_object($order))
<?php $paymentNumbersPrice = 0;?>
<?php $listenersCount = array();?>
@if($order->payment_numbers->count())
    @foreach($order->payment_numbers as $payment_number)
    <?php $paymentNumbersPrice+=$payment_number->price;?>
    @endforeach
@endif
@if($order->listeners->count())
    @foreach($order->listeners as $listener)
    <?php $listenersCount[$listener->user_id]++;?>
    @endforeach
@endif
<li class="orders-li js-orders-line {{ $order->payment->class }}">
    <div class="orders-li-head">
        <h4><a href="{{ URL::route('organization-order',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a></h4>
        <div class="orders-status">
            {{ $order->payment->title }}
            @if($order->close_status)<p>Завершен</p>@endif
        </div>
    </div>
    <div class="orders-li-body">
        <?php $price = 0.00; ?>
        <?php $coursesIDs = array(); ?>
        @if(isset($order->listeners) && is_object($order->listeners) && $order->listeners->count())
        <div class="orders-price">
            @foreach($order->listeners as $listener)
                <?php $price += $listener->price; ?>
                <?php $coursesIDs[$listener->course_id] = 1; ?>
            @endforeach
            <span class="start-price">{{ number_format($price,0,'.',' ')  }}.-</span> | <span class="end-price">{{ number_format($paymentNumbersPrice,0,'.',' ')  }}.–</span>
        </div>
        @endif
        <div class="orders-date">
            Заказ создан:
            <div>
                {{ $order->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y в H:i") }}
            </div>
        </div>
        <div class="orders-package">
            <div>В заказе {{ count($coursesIDs) }} {{ Lang::choice('курс|курса|курсов',count($coursesIDs)); }}</div>
            <div>для {{ count($listenersCount) }} {{ Lang::choice('слушателя|слушателей|слушателей',count($listenersCount)); }}</div>
        </div>
        <div class="orders-docs">
            @include(Helper::acclayout('assets.documents-min'),array('order'=>$order))
        </div>
        @if($order->payment_status == 1)
        <div class="orders-actions">
            {{ Form::open(array('url'=>URL::route('organization-order-delete',array('order_id'=>$order->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                <button type="submit" class="icon-bag-btn js-delete-order" autocomplete="off" title="Удалить заказ" data-order-number="{{ getOrderNumber($order) }}">

                </button>
            {{ Form::close() }}
        </div>
        @endif
    </div>
@endif