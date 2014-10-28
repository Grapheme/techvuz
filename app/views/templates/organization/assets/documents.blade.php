@if(User_organization::where('id',Auth::user()->id)->pluck('moderator_approve') == 1)
<ul>
    <li><a href="{{ URL::route('company-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}">Договор</a></li>
    <li><a href="{{ URL::route('company-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}">Счет</a></li>
    @if(is_object($order) && $order->close_status == 1)
    <li><a href="{{ URL::route('company-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}">Акт</a></li>
    @endif
</ul>
@endif