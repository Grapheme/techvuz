@if(count($payments))
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h3>Платежи за {{ $date }}</h3>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>№ п.п</th>
                <th>Заказ</th>
                <th>Заказчик</th>
                <th>№ плат.пор.</th>
                <th>Сумма платежа</th>
                <th>Дата платежа</th>
            </tr>
            </thead>
            <tbody>
            <?php $index = 1;?>
            @foreach($payments as $payment_id => $payment)
                <tr class="vertical-middle">
                    <td>{{ $index++; }}</td>
                    <td><a class="nowrap" href="{{ $payment['order']['link'] }}" target="_blank">№{{ $payment['order']['number'] }}</a></td>
                    <td><a href="{{ $payment['order']['purchaser']['link'] }}" target="_blank">{{ $payment['order']['purchaser']['name'] }}</a></td>
                    <td>{{ $payment['payment_number'] }}</td>
                    <td>{{ number_format($payment['price'],0,'.',' ') }} руб.</td>
                    <td>{{ (new myDateTime())->setDateString($payment['payment_date_origin'])->format('d.m.Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <p>Заказы отсутствуют</p>
@endif