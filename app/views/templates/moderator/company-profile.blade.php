@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-top-10 margin-bottom-40">{{ $profile->title }}</h2>

<div class="container-fluid moder-anket">
    <div class="row">
        <?php $accountStatus = array('Не активный','Активный','Не активирован')?>
        @if(isset($accountStatus[$profile->active]))
            @if($profile->active == 2)
            <?php $activation_date = '. До '.date("d.m.Y H:i:s",User::where('id',$profile->id)->pluck('code_life'));?>
            @else
            <?php $activation_date = ''; ?>
            @endif
            <h3>Профиль</h3>
            <div>
                {{ $accountStatus[$profile->active] }}{{ $activation_date }}
            </div>
            <div>
                Модератором {{ $profile->moderator_approve ? 'подтвержден' : 'не подтвержден' }}.
            </div>
        @endif
        <div class="employer-anket margin-bottom-40">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('moderator-company-profile-edit',$profile->id) }}">
                        <span class="icon icon-red"></span>
                    </a>
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Полное наименование организации</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->title }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">ФИО подписанта договора</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->fio_manager }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">ФИО подписанта договора в род. падеже</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->fio_manager_rod }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Должность подписанта договора</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->manager }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Документ, на основании которого действует подписант</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->statutory }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Юридический адрес</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->uraddress }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Почтовый адрес</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->postaddress }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">ОГРН</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->ogrn }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">ИНН</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->inn }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">КПП</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->kpp }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Расчетный счет</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->account_number }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Корреспондентский счет</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->account_kor_number }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Наименование банка</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->bank }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">БИК</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->bik }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">E-mail</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->email }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Контактное лицо</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->name }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Номер телефона</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->phone }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Скидка</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->discount }}%
                </div>
            </div>
        </div>
    @if($listeners->count())
        <h3>Сотрудники</h3>
        <div class="container-fluid">
            <div class="row">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                            <th class="sort sort--asc">Контакты <span class="sort--icon"></span> </th>
                        </tr>
                    @foreach($listeners as $listener)
                        <tr>
                            <td>
                                <a href="{{ URL::route('moderator-listener-profile',$listener->id) }}">{{ $listener->fio }}</a><br>
                                {{ $listener->position }}<br>
                                рег.от: {{ $listener->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y") }}
                            </td>
                            <td>
                                {{ $listener->email }}<br>
                                {{ $listener->phone }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="count-add margin-top-40">
            <?php $activeListenersIDs = array(); ?>
            @foreach($orders as $order)
                @if($order->close_status == 0)
                    @foreach($order->listeners as $listener)
                        @if($listener->start_status == 1)
                        <?php $activeListenersIDs[$listener->user_id] = 1; ?>
                        @endif
                    @endforeach
                @endif
            @endforeach
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                        <div class="count-add-sign">Обучается</div>
                        <div class="count-add-num">{{ count($activeListenersIDs) }}</div>
                        <div class="count-add-dots"></div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="count-add-sign">Всего</div>
                        <div class="count-add-num">{{ Listener::where('organization_id',$profile->id)->count() }}</div>
                        <div class="count-add-dots"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($orders->count())
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
    </div>
</div>

@stop
@section('overlays')
@stop
@section('scripts')
@stop