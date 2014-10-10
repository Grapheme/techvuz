@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->title }}</h2>
<div class="row">
    <?php $accountStatus = array('Не активный','Активный','Не активирован')?>
    @if(isset($accountStatus[$profile->active]))
        @if($profile->active == 2)
        <?php $activation_date = '. До '.date("d.m.Y H:i:s",User::where('id',$profile->id)->pluck('code_life'));?>
        @else
        <?php $activation_date = ''; ?>
        @endif
        <h3>Профиль ({{ $accountStatus[$profile->active] }}{{ $activation_date }})</h3>
    @endif
    <div class="employer-anket">
        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('moderator-company-profile-edit',$profile->id) }}">
                    <span class="icon icon-red"></span>
                </a>
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Наименование учреждения</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->title }}
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Ф.И.О. ответственного лица</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->fio_manager }}
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Должность</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->manager }}
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Уставной документ</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->statutory }}
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
                <span class="font-sm">Почтовый адрес</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->postaddress }}
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Тип счёта</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->account_type }}
            </div>
        </div>
        <div class="row margin-bottom-10">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <span class="font-sm">Номер счета</span>
            </div>
            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                {{ $profile->account_number }}
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
    </div>
@if($listeners->count())
    <h3>Сотрудники</h3>
    <div class="count-add">
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
    <div class="container-fluid">
        <div class="row">
            <table class="tech-table sortable">
                <tbody>
                    <tr>
                        <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                        <th class="sort sort--asc">Контакты <span class="sort--icon"></span> </th>
                    </tr>
                @foreach($listeners as $listener)
                    <tr>
                        <td>
                            <a href="{{ URL::route('moderator-company-listener-profile',array('company_id'=>$profile->id,'listener_id'=>$listener->id)) }}">{{ $listener->fio }}</a><br>
                            {{ $listener->position }}<br>
                            рег.от: {{ $listener->created_at->format("d.m.Y") }}
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
@endif
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop