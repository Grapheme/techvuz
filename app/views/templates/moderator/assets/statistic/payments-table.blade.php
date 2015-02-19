@if(count($orders))
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h3>Заказы за {{ $date }}</h3>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>№ п.п</th>
                <th>Заказ</th>
                <th>Заказчик</th>
                <th>Платежи</th>
                <th>Сумма заказа</th>
                <th>Дата заказа</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $index => $order)
                <tr class="vertical-middle">
                    <td>{{ $index+1; }}</td>
                    <td><a class="nowrap" href="{{ $order['link'] }}" target="_blank">№{{ $order['number'] }}</a></td>
                    <td><a href="{{ $order['purchaser']['link'] }}" target="_blank">{{ $order['purchaser']['name'] }}</a></td>
                    <td>
                    @if(count($order['payment_numbers']))
                        @foreach($order['payment_numbers'] as $payment)
                            @if($date == $payment['payment_date'] )
                            Номер поручения: {{ $payment['payment_number'] }}<br>
                            Сумма: {{ $payment['price'] }}<br>
                            Дата поступления: {{ (new myDateTime())->setDateString($payment['created_at'])->format('d.m.Y H:i') }}<br>
                            @endif
                        @endforeach
                    @endif
                    </td>
                    <td>{{ number_format($order['price'],0,'.',' ') }} руб.</td>
                    <td>{{ $order['created'] }}</td>
                    <td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <p>Заказы отсутствуют</p>
@endif