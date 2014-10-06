@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="employer margin-bottom-40">
        @include(Helper::acclayout('menu'))
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <h3 class="margin-bottom-20">{{ $profile->fio }}</h3>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <a class="icon--blue pull-right" href="{{ URL::route('company-listener-profile-edit',$profile->id) }}">
                        <span class="icon icon-red"></span>
                    </a>
                </div>
            </div>
        </div>
        <table class="tech-table sortable purchase-table">
            <tbody>
                <tr>
                    <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Статус <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">№ заказа <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Дата <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Документы <span class="sort--icon"></span> </th>
                </tr>
                <tr>
                    <td>
                        Не самое длинное название одного из курсов системы
                    </td>
                    <td class="self-status">
                        Обучается
                    </td>
                    <td>
                        <div class="ui-progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                    <td>
                        <a href="#">№ 432</a>
                        <div class="font-sm">
                            от 12.04.13
                        </div>
                    </td>
                    <td>
                        <div class="font-sm">
                            12.04.13
                        </div>
                    </td>
                    <td>
                       <span class="icon icon--blue icon-sertifikat"></span>  <a href="#">Сертификат</a>
                    </td>
                </tr>
                <tr class="finished-course">
                    <td>
                        Не самое длинное название одного из курсов системы
                    </td>
                    <td class="self-status">
                        Завершено
                    </td>
                    <td>
                        <div class="ui-progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                    <td>
                        <a href="#">№ 432</a>
                        <div class="font-sm">
                            от 12.04.13
                        </div>
                    </td>
                    <td>
                        <div class="font-sm">
                            12.04.13
                        </div>
                    </td>
                    <td>
                       <span class="icon icon--blue icon-sertifikat"></span>  <a href="#">Сертификат</a>
                    </td>
                </tr>
                <tr class="finished-course">
                    <td>
                        Не самое длинное название одного из курсов системы
                    </td>
                    <td class="self-status">
                        Завершено
                    </td>
                    <td>
                        <div class="ui-progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                    <td>
                        <a href="#">№ 432</a>
                        <div class="font-sm">
                            от 12.04.13
                        </div>
                    </td>
                    <td>
                        <div class="font-sm">
                            12.04.13
                        </div>
                    </td>
                    <td>
                       <span class="icon icon--blue icon-sertifikat"></span>  <a href="#">Сертификат</a>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="employer-anket margin-bottom-40">
            <h3 class="margin-bottom-30">Анкета сотрудника</h3>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Ф.И.О.</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->fio }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Должность</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->position }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Email</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->email }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Адрес</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->postaddress }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Телефон</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->phone }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Образование</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->education }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Место работы</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->place_work }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Год обучения</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->year_study }}
                </div>
            </div>
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Специальность</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->specialty }}
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