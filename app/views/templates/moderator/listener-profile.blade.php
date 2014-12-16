@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->fio }}</h2>
@if($profile['group_id'] == 5 && isset($profile->organization))
    <p class="style-light style-italic">{{ $profile->organization->title }}</p>
@endif
<div class="row">
<?php $accountStatus = array('Не активный','Активный','Не активирован')?>
@if(isset($accountStatus[$profile->active]))
    @if($profile->active == 2)
        <?php $activation_date = '. До '.date("d.m.Y H:i:s",User::where('id',$profile->id)->pluck('code_life'));?>
    @else
        <?php $activation_date = ''; ?>
    @endif
    <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('moderator-listener-profile-edit',$profile->id) }}">
        <span class="icon icon-red"></span>
    </a>
    <h3>Профиль</h3>
    <div class="style-light style-italic">
        {{ $accountStatus[$profile->active] }}{{ $activation_date }}
    </div>
    @if($profile->group_id == 6)
        <div class="style-light style-italic">
            Модератором {{ $profile->moderator_approve ? 'подтвержден' : 'не подтвержден' }}
        </div>
    @endif
@endif
    <div class="employer-anket margin-top-30 margin-bottom-40">
    @if($profile->group_id == 5)
        @include(Helper::acclayout('assets.profiles.listener'))
    @elseif($profile->group_id == 6)
        @include(Helper::acclayout('assets.profiles.individual'))
    @endif
    </div>

@if($profile->group_id == 6)
    @if($orders = Orders::where('user_id',$profile->id)->with('payment')->with('listeners')->get())
    <h3>Заказы</h3>
    <div class="container-fluid">
        <div class="row">
            <table class="table table-bordered">
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
    <div class="count-add margin-bottom-40">
        <?php $activeOrdersIDs = array(); ?>
        <?php $closedOrdersIDs = array(); ?>
        @foreach($orders as $order)
            @if($order->close_status == 0 && in_array($order->payment_status,array(2,3,4,5)))
                <?php $activeOrdersIDs[$order->id] = 1; ?>
            @endif
            @if($order->close_status == 1)
                <?php $closedOrdersIDs[$order->id] = 1; ?>
            @endif
        @endforeach
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                    <div class="count-add-sign">Активных</div>
                    <div class="count-add-num">{{ count($activeOrdersIDs) }}</div>
                    <div class="count-add-dots"></div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="count-add-sign">Закрытых</div>
                    <div class="count-add-num">{{ count($closedOrdersIDs) }}</div>
                    <div class="count-add-dots"></div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <div class="count-add-sign">Всего</div>
                    <div class="count-add-num">{{ count($orders) }}</div>
                    <div class="count-add-dots"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endif
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop