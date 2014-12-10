@if(isset($order) && is_object($order))
<tr class="vertical-middle js-orders-line">
    <td class="vertical-top">
    @if($order->payment_status == 1)
        <?php $formAction = URL::route('moderator-order-delete',array('order_id'=>$order->id));?>
    @else
        <?php $formAction = URL::route('moderator-order-arhived',array('order_id'=>$order->id));?>
    @endif
        {{ Form::open(array('url'=>$formAction, 'style'=>'display:inline-block', 'method'=>'delete')) }}
            <button type="submit" autocomplete="off" title="Удалить заказ" data-order-number="{{ getOrderNumber($order) }}" class="icon-bag-btn js-delete-order">
                
            </button>
        {{ Form::close() }}
    </td>
    <td class="vertical-top">
        <a class="nowrap" href="{{ URL::route('moderator-order-extended',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a>
    </td>
    <td class="vertical-top">
        {{ myDateTime::SwapDotDateWithTime($order->created_at) }}
        @if($order->close_status == 1)
        <br>{{ myDateTime::SwapDotDateWithTime($order->close_date) }}
        @endif
    </td>
    <td class="vertical-top">
        @if($order->organization->count())
            <a href="{{ URL::route('moderator-company-profile',$order->organization->id) }}">{{ $order->organization->title }}</a>
        @elseif($order->individual->count())
            <a href="{{ URL::route('moderator-individual-profile',$order->individual->id) }}">{{ $order->individual->fio }}</a>
        @endif
    </td>
    <td class="vertical-top">
        {{ $order->payment->title }}
        @if($order->payment_status == 2)
        <br>{{ myDateTime::SwapDotDateWithTime($order->payment_date) }}
        @endif
    </td>
    <td class="vertical-top">
        @include(Helper::acclayout('assests.documents'))
    </td>
</tr>
@endif