@if(isset($order) && is_object($order))
<tr class="vertical-middle js-orders-line">
    <td class="vertical-top">
    @if($order->payment_status == 1)
        {{ Form::open(array('url'=>URL::route('moderator-order-delete',array('order_id'=>$order->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
        <button type="submit" autocomplete="off" title="Удалить заказ" data-order-number="{{ getOrderNumber($order) }}" class="icon-bag-btn js-delete-order"></button>
        {{ Form::close() }}
    {{--@elseif(Input::has('delete') && Input::get('delete') == 1)--}}
    @endif
        {{ Form::open(array('url'=>URL::route('moderator-order-arhived',array('order_id'=>$order->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
    @if($order->close_status == 0)
        @if($order->archived == 0)
        <button type="submit" autocomplete="off" title="Заброшенный заказ" data-archived="{{ abs($order->archived - 1) }}" data-order-number="{{ getOrderNumber($order) }}" class="icon-blue-bag-btn js-archived-order"></button>
        @elseif($order->archived == 1)
            <button type="submit" autocomplete="off" title="Заброшенный заказ" data-archived="{{ abs($order->archived - 1) }}" data-order-number="{{ getOrderNumber($order) }}" class="icon-blue-bag-btn js-not-archived-order"></button>
        @endif
    @endif
        {{ Form::close() }}
    </td>
    <td class="vertical-top">
        <a class="nowrap" href="{{ URL::route('moderator-order-extended',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a>
    </td>
    <td class="vertical-top">
        {{ $order->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y в H:i") }}
        @if($order->close_status == 1)
        <br>{{ (new myDateTime())->setDateString($order->close_date)->format('d.m.Y в H:i') }}
        @endif
    </td>
    <td class="vertical-top">
        @if(!empty($order->organization))
            <a href="{{ URL::route('moderator-company-profile',$order->organization->id) }}">{{ $order->organization->title }}</a>
        @elseif(!empty($order->individual))
            <a href="{{ URL::route('moderator-listener-profile',$order->individual->id) }}">{{ $order->individual->fio }}</a>
        @endif
    </td>
    <td class="vertical-top">
        {{ $order->payment->title }}
        @if($order->payment_status == 2)
        <br>{{ myDateTime::SwapDotDateWithTime($order->payment_date) }}
        @endif
    </td>
    <td class="vertical-top">
        @include(Helper::acclayout('assets.documents'))
    </td>
</tr>
@endif