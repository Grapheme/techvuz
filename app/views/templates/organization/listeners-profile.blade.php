@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="employer margin-bottom-40">
        @include(Helper::acclayout('menu'))
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                    <h3 class="margin-bottom-20">{{ $profile->fio }}</h3>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                    <a class="icon--blue pull-right" href="{{ URL::route('organization-listener-profile-edit',$profile->id) }}">
                        <span class="icon icon-red"></span>
                    </a>
                </div>
            </div>
        </div>
        @if($profile->study->count())
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
                @foreach($profile->study as $study)
                <tr {{ ($study->start_status == 1 && $study->over_status == 1) ? 'class="finished-course"' : '' }}>
                    <td>{{ $study->course->title }}</td>
                    <td class="self-status">
                    @if($study->start_status == 0 && $study->over_status == 0)
                        Не начато
                    @elseif($study->start_status == 1 && $study->over_status == 1)
                        Завершено
                    @else
                        Обучается
                    @endif
                    </td>
                    <td>
                    @if($study->start_status == 0 && $study->over_status == 0)

                    @else
                        <div class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress() }} clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    @endif
                    </td>
                    <td>
                        <a href="#">№ {{ $study->order->number }}</a>
                        <div class="font-sm">
                            от {{ myDateTime::SwapDotDateWithOutTime($study->order->created_at) }}
                        </div>
                    </td>
                    <td>
                        <div class="font-sm">
                        @if($study->start_status == 0 && $study->over_status == 0)

                        @elseif($study->start_status == 1 && $study->over_status == 1)
                            {{ myDateTime::SwapDotDateWithTime($study->over_date) }}
                        @else
                            {{ myDateTime::SwapDotDateWithTime($study->start_date) }}
                        @endif
                        </div>
                    </td>
                    <td>
                        @if($study->start_status == 0 && $study->over_status == 0)

                        @elseif($study->start_status == 1 && $study->over_status == 1)
                           <span class="icon icon--blue icon-sertifikat"></span>  <a href="#">Сертификат</a>
                        @else

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
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
                    <span class="font-sm">Ф.И.О. в дат. падеже</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->fio_dat }}
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
                    <span class="font-sm">Адрес</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->postaddress }}
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
                    <span class="font-sm">Email</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->email }}
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
                    <span class="font-sm">Номер и дата выдачи документа об образовании</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->education_document_data }}
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
            <div class="row margin-bottom-10">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <span class="font-sm">Наименование учебного заведения</span>
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                    {{ $profile->educational_institution }}
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