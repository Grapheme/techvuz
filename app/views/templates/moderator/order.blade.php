@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<div class="container-fluid">
    <div class="row moder-order">
        <div>
            <div class="orders-li-head">
                <?php $order_price = 0;?>
                <a href="{{ URL::route('moderator-order-edit',$order->id) }}" class="icon--blue pull-right"><span class="icon icon-red"></span></a>
                <h2>Заказ №{{ getOrderNumber($order) }}</h2>
                <div class="style-light margin-bottom-10">
                    Заказчик:
                @if(!empty($order->organization))
                    <a class="icon--blue" href="{{ URL::route('moderator-company-profile',$order->organization->id) }}">{{ $order->organization->title }}</a>
                @elseif(!empty($order->individual))
                    <a class="icon--blue" href="{{ URL::route('moderator-listener-profile',$order->individual->id) }}">{{ $order->individual->fio }}</a>
                @endif
                </div>
                @foreach($order->listeners as $listener)
                <?php $order_price += $listener->price;?>
                @endforeach
                <div class="orders-status style-light">
                    {{ $order->payment->title }}
                </div>
            </div>
            <div class="orders-li-body">
                <div class="orders-price">
                    <span class="start-price">{{ number_format($order_price,0,'.',' ') }}.-</span>
                </div>
                <div class="orders-date">
                    Заказ создан: {{ $order->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y в H:i") }}
                </div>
            </div>
        </div>
        <div>
            <div class="moder-order-docs margin-top-20">
                Документы:
                @include(Helper::acclayout('assets.documents'))
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
            <div class="payment-select margin-top-10 pull-right">
                <div class="select-payments margin-bottom-10 margin-top-30 text-right">
                    <a href="javasccript:void(0);" class="font-sm margin-right-10 js-check-all-payments">Добавить всех</a>
                    <a href="javasccript:void(0);" class="font-sm js-uncheck-all-payments">Убрать всех</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    $total_summa = 0;
    $payment_summa = 0;
    foreach($order->payment_numbers as $payment_number):
        $payment_summa += $payment_number->price;
    endforeach;

    foreach($courses as $course_id => $course):
        if(count($course['listeners'])):
            foreach($course['listeners'] as $index => $listener):
                $total_summa += $listener->price;
            endforeach;
        endif;
    endforeach;
?>

<table class="tech-table payments-table margin-bottom-30">
    <tr>
        <th> Курс </th>
        <th> Слушатели </th>
        <th> Стоимость </th>
        <th> Доступ </th>
    </tr>
@foreach($courses as $course_id => $course)
    @if(count($course['listeners']))
        @foreach($course['listeners'] as $index => $listener)
    <tr>
        @if($index == 0)
        <td rowspan="{{ count($course['listeners']) }}">{{ $course['course']['code'] }}. {{{ $course['course']['title'] }}}</td>
        @endif
        <td>
            <a href="{{ URL::route('moderator-listener-profile',$listener->user_listener->id) }}">{{ $listener->user_listener->fio }}</a>
        </td>
        <td class="purchase-price">{{ $listener->price }} руб.</td>
        <td>
            {{ Form::checkbox('access_status',$listener->id,$listener->access_status,array('class'=>'js-set-listener-access','autocomplete'=>'off')) }}
        </td>
    </tr>
        @endforeach
    @endif
@endforeach
</table>
<button type="submit" autocomplete="off" class="btn btn--bordered btn--blue js-set-listeners-access btn-form-submit" data-action = "{{ URL::route('order-listener-access',array('order_id'=>$order->id)) }}">
    <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Применить</span>
</button>
<div class="sum-block margin-bottom-40">
    <div class="count-add">
        <div class="container-fluid">
            <div class="row no-gutter margin-bottom-20">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="count-add-sign">Сумма заказа</div>
                    <div class="count-add-num">{{ number_format($total_summa, 0, ',', ' ') }} руб.</div>
                    <div class="count-add-dots"></div>
                </div>
            </div>
            <div class="row no-gutter margin-bottom-20">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="count-add-sign">Оплачено</div>
                    <div class="count-add-num">{{ number_format($payment_summa, 0, ',', ' ') }} руб.</div>
                    <div class="count-add-dots"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<h3 class="margin-bottom-40">Платежи</h3>

@if($order->payment_numbers->count())
<table class="tech-table margin-bottom-30">
    <tr>
        <th> Дата </th>
        <th> Сумма </th>
        <th> № П/П </th>
        <th> </th>
    </tr>
    @foreach($order->payment_numbers as $payment_number)
    <tr data-paymentid="{{ $payment_number->id }}">
        <td class="js-payment-date" data-payment-data="{{ myDateTime::SwapDotDateWithOutTime($payment_number->payment_date) }}">{{ myDateTime::SwapDotDateWithOutTime($payment_number->payment_date) }}</td>
        <td class="js-payment-price purchase-price" data-payment-price="{{ $payment_number->price }}">{{ number_format($payment_number->price, 0, ',', ' ') }} руб.</td>
        <td class="js-payment-id" data-payment-number="{{ $payment_number->payment_number }}">{{ $payment_number->payment_number }}</td>
        <td>
            <a href="javascript:void(0);" class="margin-right-10 js-edit-payment" title="Редактировать">
                <span class="icon icon-red icon--blue"></span>
            </a>
            {{ Form::open(array('url'=>URL::route('payment-order-number-delete',array('order_id'=>$order->id,'payment_order_id'=>$payment_number->id)),'style'=>"display:inline-block", 'method'=>'DELETE')) }}
                <button type="submit" title="Удалить" class="js-delete-payment"><span class="icon icon-cancel"></span></button>
           {{ Form::close() }}
        </td>
    </tr>
    @endforeach
</table>
@endif

@if($payment_summa < $total_summa)
<div class="margin-bottom-40">
    <a href="javascript:void(0);" class="btn btn--bordered btn--blue" data-toggle="modal" data-target="#regPayment">
        Добавить платеж
    </a>
</div>
@endif
<div class="modal fade add-payment-modal" id="regPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавление платежа</h4>
            </div>
            <div class="modal-body">
                @include(Helper::acclayout('forms.payment-number-insert'))
            </div>
        </div>
    </div>
</div>
<div class="modal fade edit-payment-modal" id="editPayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Редактирование платежа</h4>
            </div>
            <div class="modal-body">
                @include(Helper::acclayout('forms.payment-number-edit'))
            </div>
        </div>
    </div>
</div>
<div class="modal fade delete-payment-modal" id="deletePayment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Вы действительно хотите удалить платеж?</h4>
            </div>
            <div class="modal-body margin-bottom-40 clearfix text-right">
                <a class="btn btn--bordered btn--blue margin-right-10" data-dismiss="modal" href="#">Отмена</a>
                <a id="confirmRemove" class="btn btn--bordered btn--danger" data-dismiss="modal" href="#">Удалить</a>
            </div>
        </div>
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop