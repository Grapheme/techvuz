@if(isset($order) && is_object($order))
<tr class="vertical-middle js-orders-line">
    <td>
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
    <td><a href="{{ URL::route('moderator-order-extended',$order->id) }}">Заказ №{{ getOrderNumber($order) }}</a></td>
    <td>
        {{ myDateTime::SwapDotDateWithTime($order->created_at) }}
        @if($order->close_status == 1)
        <br>{{ myDateTime::SwapDotDateWithTime($order->close_date) }}
        @endif
    </td>
    <td>
        @if($order->organization->count())
            <a href="{{ URL::route('moderator-company-profile',$order->organization->id) }}">{{ $order->organization->title }}</a>
        @elseif($order->individual->count())
            <a href="{{ URL::route('moderator-individual-profile',$order->individual->id) }}">{{ $order->individual->fio }}</a>
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
            <li><a href="{{ URL::route('moderator-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}">Договор</a></li>
            <li><a href="{{ URL::route('moderator-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}">Счет</a></li>
            <li><a href="{{ URL::route('moderator-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}">Акт</a></li>
            <li><a href="{{ URL::route('moderator-order-request',array('order_id'=>$order->id,'format'=>'pdf')) }}">Заявки</a></li>
            <li><a href="{{ URL::route('moderator-order-enrollment',array('order_id'=>$order->id,'format'=>'pdf')) }}">Приказ о зачислении</a></li>
            <li><a href="{{ URL::route('moderator-order-completion',array('order_id'=>$order->id,'format'=>'pdf')) }}">Приказ об окончании</a></li>
            <li><a href="{{ URL::route('moderator-order-class-schedule',array('order_id'=>$order->id,'format'=>'pdf')) }}">Расписание занятий</a></li>
            <li><a href="{{ URL::route('moderator-order-statements',array('order_id'=>$order->id,'format'=>'pdf')) }}">Заявления</a></li>
            <li><a href="{{ URL::route('moderator-order-explanations',array('order_id'=>$order->id,'format'=>'pdf')) }}">Пояснения к документам</a></li>
            <li><a href="{{ URL::route('moderator-order-browsing-history',array('order_id'=>$order->id,'format'=>'pdf')) }}">Журнал посещений</a></li>
            <li><a href="{{ URL::route('moderator-order-result-certification',array('order_id'=>$order->id,'format'=>'pdf')) }}">Результаты аттестации</a></li>
            <li><a href="{{ URL::route('moderator-order-attestation-sheet',array('order_id'=>$order->id,'format'=>'pdf')) }}">Аттестационные ведомости</a></li>
        </ul>
    </td>
</tr>
@endif