<ul>
    <li><a href="{{ URL::route('company-order-contract',$order->id) }}">Договор</a></li>
    <li><a href="{{ URL::route('company-order-invoice',$order->id) }}">Счет</a></li>
    @if(is_object($order) && $order->close_status == 1)
    <li><a href="{{ URL::route('company-order-act',$order->id) }}">Акт</a></li>
    @endif
</ul>