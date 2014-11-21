<ul>
    <li><a href="{{ URL::route('moderator-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}">Договор</a></li>
    <li><a href="{{ URL::route('moderator-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}">Счет</a></li>
    <li><a href="{{ URL::route('moderator-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}">Акт</a></li>
</ul>