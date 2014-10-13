@if(isset($order) && is_object($order))
<li class="orders-li {{ $order->payment->class }}">
    <div class="orders-li-head">
        <h4>Заказ №{{ $order->number }}</h4>
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
            <div>В заказе {{ count($coursesIDs) }} {{ Lang::choice('курс|курса|курсов',count($coursesIDs)); }}</div>
            <div>для {{ $order->listeners->count() }} {{ Lang::choice('слушателя|слушателей|слушателей',$order->listeners->count()); }}</div>
        </div>
        @if(in_array($order->payment_status,array(2,3)) && in_array($order->close_status,array(0,1)))
        <div class="orders-docs">
            Посмотреть <a href="#">документы</a>
        </div>
        @endif
    </div>
@endif