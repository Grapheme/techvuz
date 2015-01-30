@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employer-anket margin-bottom-40">
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <h3 class="no-margin">Профиль</h3>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                 @if(AccountsOrganizationController::activism() === FALSE)
                    <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('organization-profile-edit') }}">
                        Редактировать <span class="icon icon-red"></span>
                    </a>
                @endif
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
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop