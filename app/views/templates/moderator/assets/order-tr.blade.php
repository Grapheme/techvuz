@if(isset($order) && is_object($order))
<tr class="vertical-middle">
    <td> <a href="{{ URL::route('moderator-order-extended',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a></td>
    <td>
        {{ myDateTime::SwapDotDateWithTime($order->created_at) }}
        @if($order->close_status == 1)
        <br>{{ myDateTime::SwapDotDateWithTime($order->close_date) }}
        @endif
    </td>
    <td>
        @if($order->organization->count())
            {{ $order->organization->title }}
        @elseif($order->individual->count())
            {{ $order->individual->fio }}
        @endif
    </td>
    <td>
        {{ $order->payment->title }}
        @if($order->payment_status == 2)
        <br>{{ myDateTime::SwapDotDateWithTime($order->payment_date) }}
        @endif
    </td>
    <td>
        <ul>
            <li><a href="{{ URL::route('moderator-order-contract',array('order_id'=>$order->id,'format'=>'word')) }}">Договор</a></li>
            <li><a href="{{ URL::route('moderator-order-invoice',array('order_id'=>$order->id,'format'=>'word')) }}">Счет</a></li>
            <li><a href="{{ URL::route('moderator-order-act',array('order_id'=>$order->id,'format'=>'word')) }}">Акт</a></li>
        </ul>
    </td>
</tr>
@endif