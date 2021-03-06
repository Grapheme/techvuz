@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
 <?php
$orders = Orders::orderBy('payment_status')->orderBy('created_at','DESC')->with('payment', 'organization', 'individual')->get();
?>
<h3>Список заказов</h3>
<div class="tabs usual-tabs">
    <ul>
        <li>
            <?php $count_orders = 0; ?>
            @foreach($orders as $order)
                @if($order->payment_status == 1 && $order->close_status == 0 && $order->study_status == 0 && $order->archived == 0)
                    <?php $count_orders++ ; ?>
                @endif
            @endforeach
            <a href="#tabs-11">Новые {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
        </li>
        <li>
            <?php $count_orders = 0; ?>
            @foreach($orders as $order)
                @if((in_array($order->payment_status,array(2,3,4,5)) || $order->study_status == 1) && $order->close_status == 0 && $order->archived == 0)
                    <?php $count_orders++ ; ?>
                @endif
            @endforeach
            <a href="#tabs-12">Активные {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
        </li>
        <li>
            <?php $count_orders = 0; ?>
            @foreach($orders as $order)
                @if($order->close_status == 1)
                    <?php $count_orders++ ; ?>
                @endif
            @endforeach
            <a href="#tabs-13">Завершенные {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
        </li>
        <li>
            <?php $count_orders = 0; ?>
            @foreach($orders as $order)
                @if($order->archived == 1)
                    <?php $count_orders++ ; ?>
                @endif
            @endforeach
            <a href="#tabs-15">Заброшенные {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
        </li>
        <li>
            <?php $count_orders = 0; ?>
            @foreach($orders as $order)
                <?php $count_orders++ ; ?>
            @endforeach
            <a href="#tabs-14">Все {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
        </li>
    </ul>
    <div id="tabs-11" class="js-tab-current">
        <form class="orders-search margin-bottom-20">            
            <fieldset>
                <input type="text" placeholder="Укажите номер заказа или заказчика">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>№ заказа</th>
                    <th>Создан<br>Закрыт</th>
                    <th>Заказчик</th>
                    <th>Статус оплаты<br>Дата оплаты</th>
                    <th>Документы</th>
                </tr>
            </thead>
            <tbody>
        @foreach($orders as $order)
            @if($order->payment_status == 1 && $order->close_status == 0 && $order->study_status == 0 && $order->archived == 0)
                @include(Helper::acclayout('assets.order-tr'))
            @endif
        @endforeach
            </tbody>
        </table>
    </div>
    <div id="tabs-12" class="js-tab-current">
        <form class="orders-search margin-bottom-20">            
            <fieldset>
                <input type="text" placeholder="Укажите номер заказа или заказчика">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>№ заказа</th>
                    <th>Создан<br>Закрыт</th>
                    <th>Заказчик</th>
                    <th>Статус оплаты<br>Дата оплаты</th>
                    <th>Документы</th>
                </tr>
            </thead>
            <tbody>
        @foreach($orders as $order)
            @if((in_array($order->payment_status,array(2,3,4,5)) || $order->study_status == 1) && $order->close_status == 0 && $order->archived == 0)
                @include(Helper::acclayout('assets.order-tr'))
            @endif
        @endforeach
            </tbody>
        </table>
    </div>
    <div id="tabs-13" class="js-tab-current">
        <form class="orders-search margin-bottom-20">            
            <fieldset>
                <input type="text" placeholder="Укажите номер заказа или заказчика">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>№ заказа</th>
                    <th>Создан<br>Закрыт</th>
                    <th>Заказчик</th>
                    <th>Статус оплаты<br>Дата оплаты</th>
                    <th>Документы</th>
                </tr>
            </thead>
            <tbody>
        @foreach($orders as $order)
            @if($order->close_status == 1)
                @include(Helper::acclayout('assets.order-tr'))
            @endif
        @endforeach
            </tbody>
        </table>
    </div>
    <div id="tabs-15" class="js-tab-current">
        <form class="orders-search margin-bottom-20">
            <fieldset>
                <input type="text" placeholder="Укажите номер заказа или заказчика">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th></th>
                <th>№ заказа</th>
                <th>Создан<br>Закрыт</th>
                <th>Заказчик</th>
                <th>Статус оплаты<br>Дата оплаты</th>
                <th>Документы</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                @if($order->archived == 1)
                    @include(Helper::acclayout('assets.order-tr'))
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
    <div id="tabs-14" class="js-tab-current">
        <form class="orders-search margin-bottom-20">            
            <fieldset>
                <input type="text" placeholder="Укажите номер заказа или заказчика">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>№ заказа</th>
                    <th>Создан<br>Закрыт</th>
                    <th>Заказчик</th>
                    <th>Статус оплаты<br>Дата оплаты</th>
                    <th>Документы</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                @include(Helper::acclayout('assets.order-tr'))
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop