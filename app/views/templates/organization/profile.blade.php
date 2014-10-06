@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employer-anket">
            <h3>Анкета сотрудника</h3>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Ф.И.О.</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    Авазаров Михаил Юрьевич
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Должность</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    Старший механик
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Email</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    avazarov@company.com
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Адрес</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    г. Батайск, ул. Ленина, 35, офис 120
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Телефон</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    +7 (910) 432 - 10 - 45
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Образование</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    Высшее профессиональное
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Место работы</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    ГОУ ВПО "БТИ-РГУ"
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Год обучения</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    2010
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Специальность</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    Специалист по ликвидации безработицы
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