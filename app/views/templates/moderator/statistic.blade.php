@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2>Статистика</h2>
<div class="row">
    <div class="input">
        @include(Helper::acclayout('forms.statistic'))
    </div>
</div>
<h3 class="margin-bottom-40">Сводная статистика за период</h3>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Количество заказов</th>
                <th>Сумма заказов</th>
                <th>Количество платежных поручений</th>
                <th>Сумма платежных поручений</th>
            </tr>
            </thead>
            <tbody>
                <tr class="vertical-middle">
                    <td>{{ $orders_extended_counts['orders']; }}</td>
                    <td>{{ number_format($orders_extended_counts['price'],0,'.',' ') }} руб.</td>
                    <td>{{ $payments_extended_counts['payments']; }}</td>
                    <td>{{ number_format($payments_extended_counts['price'],0,'.',' ') }} руб.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<h3 class="margin-bottom-40">Статистика заказов</h3>
<div id="orderschart" style="width:848px;height: 300px;" class="chart"></div>
@if(count($orders_extended))
<div id="orderschartextended" class="margin-top-20 margin-bottom-20">
@foreach($orders_extended as $index => $order_extended)
    @if($index == 'total')
    <div data-order-date="{{ $index }}" class="order-extended">{{ $order_extended }}</div>
    @else
    <div data-order-date="{{ $index }}" class="order-extended hidden">{{ $order_extended }}</div>
    @endif
@endforeach
</div>
@endif
<h3 class="margin-bottom-40 margin-top-40">Статистика платежей</h3>
<div id="paymentschart" style="width:848px;height: 300px;" class="chart"></div>
@if(count($payments_extended))
<div id="paymentschartextended" class="margin-top-20 margin-bottom-20">
@foreach($payments_extended as $index => $payment_extended)
    <div data-order-date="{{ $index }}" class="payment-extended hidden">{{ $payment_extended }}</div>
@endforeach
</div>
@endif
@if($account_selected)
<h3 class="margin-bottom-40">Список платежных поручением</h3>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>№ п.п</th>
                <th>Заказ</th>
                <th>Номер поручения</th>
                <th>Сумма</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php $index = 0; ?>
            @foreach($payments_list as $order_number => $payments)
                @foreach($payments as $payment)
                <tr class="vertical-middle">
                    <td>{{ ++$index; }}</td>
                    <td><a class="nowrap" href="{{ URL::route('moderator-order-extended',$payment['order_id']) }}">{{ $order_number }}</a></td>
                    <td>{{ $payment['payment_number'] }}</td>
                    <td>{{ $payment['price'] }}</td>
                    <td>{{ myDateTime::SwapDotDateWithOutTime($payment['payment_date']) }}</td>
                </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/vendor/jquery.ui.datepicker-ru.js') }}
{{ HTML::script('js/flot/jquery.flot.js') }}
{{ HTML::script('js/flot/jquery.flot.categories.js') }}
{{ HTML::script('js/flot/jquery.flot.tooltip.js') }}
<script>
    $(function(){
        $("#select-period-begin").datepicker({
            constrainInput: true,
            autoSize: true,
            firstDay: 1,
            minDate: "01.10.2014",
            maxDate: '0D',
            defaultDate: "-2w",
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate){
                $("#select-period-end").datepicker("option","minDate",selectedDate);
            }
        });
        $("#select-period-end").datepicker({
            constrainInput: true,
            autoSize: true,
            firstDay: 1,
            defaultDate: "0D",
            minDate: "01.10.2014",
            maxDate: '0D',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate){
                $("#select-period-begin").datepicker("option","maxDate",selectedDate);
            }
        });
    });

    var $chrt_border_color  = "#efefef";
    var $color_orders        = "#FFCC00";
    var $color_paymetns        = "#ff0000";

    var $orders = []; var $payments = [];
    @foreach($orders_chart as $date => $count)
    $orders.push([ "{{ $date }}" , {{ $count }} ])
    @endforeach
    @foreach($payments_chart as $date => $summa)
    $payments.push([ "{{ $date }}" , {{ $summa }} ])
    @endforeach

    var $options = {
        xaxis : {mode: "categories",tickLength: 0},
        yaxis : {show : true},
        series : {
            bars : {show : true,barWidth: 0.3,align: "center"},
            lines : {show : true,barWidth: 0.6,align: "center"},
            points: {show: false},
            shadowSize : 0
        },
        selection : {mode : "x"},
        grid : {hoverable : true,clickable : true,tickColor : $chrt_border_color,borderWidth : 0,borderColor : $chrt_border_color},
        tooltip : true,
        tooltipOpts : {content : "Заказов - %y"},
        colors : [$color_orders,$color_paymetns]
    };
    var $orders_plot = $.plot($("#orderschart"),[{data : $orders,label : "Количество заказов"}] , $options);
    $options['tooltipOpts']['content'] = "%y руб.";
    var $payments_plot = $.plot($("#paymentschart"),[{data : $payments,label : "Сумма платежей"}] , $options);

    $("#orderschart").on("plotclick", function (event, pos, item){
        if(item){
            var $dataIndex = item.dataIndex;
            $(".order-extended").addClass('hidden');
            $(".order-extended[data-order-date='"+$orders[$dataIndex][0]+"']").removeClass('hidden');
        }
    });
    $("#paymentschart").on("plotclick", function (event, pos, item){
        if(item){
            var $dataIndex = item.dataIndex;
            $(".payment-extended").addClass('hidden');
            $(".payment-extended[data-order-date='"+$payments[$dataIndex][0]+"']").removeClass('hidden');
        }
    });
</script>
@stop
@stop