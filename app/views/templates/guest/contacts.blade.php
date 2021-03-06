@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h1>{{ $page->seo->h1 }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <div class="container-fluid map-container-fluid">
        <div class="row margin-bottom-40 no-gutter">
            <div class="map-col-1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-location"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('address') }}<br>
                <a href="#" class="txt-color-blue js-show-map">Показать на карте</a>
                <div id="map_canvas"></div>
            </div>
        </div>
        <div class="row margin-bottom-20 no-gutter">
            <div class="map-col-1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-mobile"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('phones') }}
            </div>
        </div>
        <div class="row margin-bottom-20 no-gutter">
            <div class="map-col-1 col-xs-1 col-sm-1 col-md-1 col-lg-1">
                <span class="icon icon--blue icon-mail"></span>
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                <a target="_blank" href="mailto:{{ $page->block('email') }}">{{ $page->block('email') }}</a>
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
                {{ $page->block('inn') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОГРН
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('ogrn') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                КПП
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('kpp') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКПО
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('okpo') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКАТО
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('okato') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                ОКВЭД
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('pkved') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                р/сч
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('rasschet') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                к/сч
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('kschet') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                Банк
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('bank') }}
            </div>
        </div>
        <div class="row margin-bottom-10 no-gutter">
            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 font-sm">
                БИК
            </div>
            <div class=" col-xs-11 col-sm-11 col-md-11 col-lg-11">
                {{ $page->block('bik') }}
            </div>
        </div>
    </div>
    <h2 class="margin-top-40 margin-bottom-20">Информация о сотрудничестве</h2>
    <div class="desc">
        {{ $page->block('informaciya-o-sotrudnichestve') }}
    </div>
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyA4Q5VgK-858jgeSbJKHbclop_XIJs3lXs&sensor=true"></script>
@stop
@section('overlays')
@stop
@section('scripts')
    <script src="//api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU" type="text/javascript"></script>
@stop