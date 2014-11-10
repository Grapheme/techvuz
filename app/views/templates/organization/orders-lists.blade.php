@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $orders = Orders::where('user_id',Auth::user()->id)->where('archived',FALSE)->orderBy('payment_status')->orderBy('created_at','DESC')->with('payment','payment_numbers')->with(array('listeners'=>function($query){
        $query->with('listener');
        $query->with('course');
    }))->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
            <h3>Заказы</h3>
            <div class="tabs usual-tabs">
                <ul>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            @if($order->payment_status == 1 && $order->close_status == 0)
                                <?php $count_orders++ ; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-11">Новые {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
                    </li>
                    <li>
                        <?php $count_orders = 0; ?>
                        @foreach($orders as $order)
                            @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
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
                            <?php $count_orders++ ; ?>
                        @endforeach
                        <a href="#tabs-14">Все {{ $count_orders ? '<span class="filter-count">'.$count_orders.'</span>' : '' }}</a>
                    </li>
                </ul>
                <div id="tabs-11" class="js-tab-current">
                    <ul class="orders-ul">
                    @if(hasCookieData('ordering'))
                        @include(Helper::acclayout('assets.temp-order'))
                    @endif
                    @foreach($orders as $order)
                        @if($order->payment_status == 1 && $order->close_status == 0)
                            @include(Helper::acclayout('assets.order'))
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-12" class="js-tab-current">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        @if(in_array($order->payment_status,array(2,3)) && $order->close_status == 0)
                            @include(Helper::acclayout('assets.order'))
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-13" class="js-tab-current">
                    <ul class="orders-ul">
                    @foreach($orders as $order)
                        @if($order->close_status == 1)
                            @include(Helper::acclayout('assets.order'))
                        @endif
                    @endforeach
                    </ul>
                </div>
                <div id="tabs-14" class="js-tab-current">
                    <ul class="orders-ul">
                    @if(hasCookieData('ordering'))
                        @include(Helper::acclayout('assets.temp-order'))
                    @endif
                    @foreach($orders as $order)
                        @include(Helper::acclayout('assets.order'))
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('js/vendor/SmartNotification.min.js') }}
{{ HTML::script('js/system/messages.js') }}
{{ HTML::script('theme/js/organization.js') }}
@stop