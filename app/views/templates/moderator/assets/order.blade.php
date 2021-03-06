@if(isset($order) && is_object($order))
<?php $paymentNumbersPrice = 0;?>
<?php $listenersCount = array();?>
@if(count($order->payment_numbers))
    @foreach($order->payment_numbers as $payment_number)
    <?php $paymentNumbersPrice+=$payment_number->price;?>
    @endforeach
@endif
@if(count($order->listeners))
    @foreach($order->listeners as $listener)
    <?php $listenersCount[$listener->user_id]++;?>
    @endforeach
@endif
<li class="orders-li {{ $order->payment->class }} js-orders-line">
    <div class="orders-li-head">
        <h4><a href="{{ URL::route('moderator-order-extended',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a></h4>
        <div class="orders-status">
            {{ $order->payment->title }}
        </div>
    </div>
    <div class="orders-li-body">
        @if(isset($order->listeners) && is_object($order->listeners) && $order->listeners->count())
        <div class="orders-price">
            <?php $price = 0.00; ?>
            <?php $coursesIDs = array(); ?>
            @foreach($order->listeners as $listener)
                <?php $price += $listener->price; ?>
                <?php $coursesIDs[$listener->course_id] = 1; ?>
            @endforeach
            <span class="start-price">{{ number_format($price,0,'.',' ')  }}.-</span> | <span class="end-price">{{ number_format($paymentNumbersPrice,0,'.',' ')  }}.–</span>
        </div>
        @endif
        <div class="orders-company">
            Заказчик:
            <div>
            @if(!empty($order->organization))
                <a href="{{ URL::route('moderator-company-profile',$order->organization->id) }}">{{ $order->organization->title }}</a>
            @elseif(!empty($order->individual))
                <a href="{{ URL::route('moderator-listener-profile',$order->individual->id) }}">{{ $order->individual->fio }}</a>
            @endif
            </div>
        </div>
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
            Документы:
            @include(Helper::acclayout('assets.documents'),array('order'=>$order))
        </div>
        <div class="orders-actions">
        @if($order->payment_status == 1)
            <?php $formAction = URL::route('moderator-order-delete',array('order_id'=>$order->id));?>
        @else
            <?php $formAction = URL::route('moderator-order-arhived',array('order_id'=>$order->id));?>
        @endif
        {{ Form::open(array('url'=>$formAction, 'style'=>'display:inline-block', 'method'=>'delete')) }}
            <button type="submit" autocomplete="off" title="Удалить заказ" data-order-number="{{ getOrderNumber($order) }}" class="icon-bag-btn js-delete-order"></button>
        {{ Form::close() }}
        </div>
    </div>
@endif