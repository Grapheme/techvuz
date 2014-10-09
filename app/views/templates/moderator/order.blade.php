@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <h2 class="margin-bottom-40">Заказ №{{ $order->number }}</h2>
    </div>
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <div class="payment-select pull-right">
            {{ Form::select('payments_status',PaymentStatus::lists('title','id'),$order->payment_status,array('class'=>'select')) }}
            <div class="select-payments margin-top-30 text-right">
                <a href="javasccript:void(0);" class="font-sm margin-right-10 js-check-all-payments">Добавить всех</a>
                <a href="javasccript:void(0);" class="font-sm js-uncheck-all-payments">Убрать всех</a>
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
            <a href="javascript:void(0);">{{ $listener->user_listener->fio }}</a>
        </td>
        <td class="purchase-price">{{ $listener->price }} руб.</td>
        <td>
            {{ Form::checkbox('access_status',1,$listener->access_status) }}
        </td>
    </tr>
        @endforeach
    @endif
@endforeach
</table>
<div class="sum-block margin-bottom-40">
    <div class="count-add">
        <div class="container-fluid">
            <div class="row no-gutter margin-bottom-20">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="count-add-sign">Оплачено</div>
                    <div class="count-add-num">{{ number_format($payment_summa, 0, ',', ' ') }} руб.</div>
                    <div class="count-add-dots"></div>
                </div>
            </div>
            <div class="row no-gutter margin-bottom-20">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="count-add-sign">Итоговая сумма</div>
                    <div class="count-add-num">{{ number_format($total_summa, 0, ',', ' ') }} руб.</div>
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
                <span class="icon icon-red"></span>
            </a>
            <form method="DELETE" action="{{ URL::route('payment-order-number-delete',$payment_number->id) }}" style="display:inline-block">
                <button type="submit" title="Удалить" class="js-delete-payment"><span class="icon icon-cancel"></span></button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endif

<form class="form-delete-payment" action="#" method="POST">
    <input class="delete-payment-id" type="hidden">
</form>
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