@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h2>{{ $page->block('top_h2') }}</h2>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>

    <div class="container-fluid">
        <div class="row margin-bottom-20 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-location"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('address') }}
                <a href="#" class="txt-color-blue font-sm">Показать на карте</a>
            </div>
        </div>
        <div class="row margin-bottom-20 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-mobile"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('phones') }}
            </div>
        </div>
        <div class="row margin-bottom-20 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-mail"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                <a href="mailto:{{ $page->block('email') }}">{{ $page->block('email') }}</a>
            </div>
        </div>
    </div>
    <h2 class="margin-top-40 margin-bottom-20">{{ $page->block('center_h2') }}</h2>

    <div class="container-fluid margin-bottom-40">
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ИНН
            </div>
            <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                6164990260
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОГРН
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                1126100001720
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                КПП
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                616401001
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКПО
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                38426411
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКАТО
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                60401372000
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКВЭД
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                80.42
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                р/сч
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                4070381082605000000
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                к/сч
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                30101810500000000207
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                Банк
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                ОАО Альфа-Банк г.  Ростов-на-Дону
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                БИК
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                046015207
            </div>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop