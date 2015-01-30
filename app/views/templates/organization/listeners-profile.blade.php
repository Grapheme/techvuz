@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="employer margin-bottom-40">
        @include(Helper::acclayout('menu'))
        
        <h3 class="margin-bottom-20">{{ $profile->fio }}</h3>
        @if($profile->study->count())
        <table class="tech-table sortable purchase-table">
            <thead>
                <tr>
                    <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Заказ <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Статус <span class="sort--icon"></span> </th>
                </tr>
            </thead>
            <tbody>                
                @foreach($profile->study as $study)
                <tr {{ ($study->start_status == 1 && $study->over_status == 1) ? 'class="finished-course"' : '' }}>
                    <td class="vertical-top">{{ $study->course->code  }}. {{ $study->course->title }}</td>
                    <td class="vertical-top">
                        <a href="{{ URL::route('organization-order',$study->order->id) }}"> №{{ getOrderNumber($study->order) }}</a>
                        <div class="font-sm nowrap">
                            от {{ $study->order->created_at->format("d.m.Y") }}
                        </div>
                    </td>
                    <td class="self-status vertical-top">
                        <span>
                            @if($study->start_status == 0 && $study->over_status == 0)
                                Не обучается
                            @elseif($study->start_status == 1 && $study->over_status == 1)
                                Обучение завершено
                            @else
                                Обучается с
                            @endif
                        </span>
                        <span>
                            @if($study->start_status == 0 && $study->over_status == 0)

                            @elseif($study->start_status == 1 && $study->over_status == 1)
                                {{ myDateTime::SwapDotDateWithOutTime($study->over_date) }}
                            @else
                                {{ myDateTime::SwapDotDateWithOutTime($study->start_date) }}
                            @endif
                        </span>

                        @if($study->start_status == 0 && $study->over_status == 0)

                        @else
                            <div title="{{ Lang::get('interface.STUDY_PROGRESS.'.getCourseStudyProgress($study)) }}" class="ui-progress-bar bar-1 completed-{{ getCourseStudyProgress($study) }} margin-top-20 margin-bottom-20 margin-auto clearfix">
                                <div class="bar-part bar-part-1"></div>
                                <div class="bar-part bar-part-2"></div>
                                <div class="bar-part bar-part-3"></div>
                            </div>
                        @endif

                        @if($study->start_status == 0 && $study->over_status == 0)

                        @elseif($study->start_status == 1 && $study->over_status == 1)
                            <a class="style-normal nowrap" href="{{ URL::route('organization-order-certificate',array('order_id'=>$study->order_id,'course_id'=>$study->id,'listener_id'=>$study->user_id,'format'=>'pdf')) }}">
                                <span class="icon icon-sertifikat"></span> Удостоверение
                            </a>
                        @else

                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <div class="employer-anket margin-top-20 margin-bottom-40">
            <div class="row margin-bottom-10">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <h3 class="no-margin">Анкета сотрудника</h3>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                @if(AccountsOrganizationController::activism($profile->id) === FALSE)
                    <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('organization-listener-profile-edit',$profile->id) }}">
                        Редактировать <span class="icon icon-red"></span>
                    </a>
                @else
                    <a title="{{ Lang::get('interface.ACCOUNT_STATUS.blocked_edit_listener_profile') }}" class="icon--blue icon--disabled pull-right margin-top-30">
                       Редактирование заблокировано <span class="icon icon-red"></span>
                    </a>
                @endif
                </div>
            </div>            
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