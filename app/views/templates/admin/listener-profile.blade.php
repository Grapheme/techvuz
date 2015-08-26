@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->fio }}</h2>
@if($profile['group_id'] == 5 && isset($profile->organization))
    <p class="style-light style-italic">{{ $profile->organization->title }}</p>
@endif
<div class="container-fluid">
    <div class="row">
    <?php $accountStatus = array('Не активный','Активный','Не активирован')?>
    @if(isset($accountStatus[$profile->active]))
        @if($profile->active == 2)
            <?php $activation_date = '. До '.date("d.m.Y H:i:s",User::where('id',$profile->id)->pluck('code_life'));?>
        @else
            <?php $activation_date = ''; ?>
        @endif
        <h3>Профиль</h3>
        <div class="style-light style-italic">
            E-mail адрес: {{ $accountStatus[$profile->active] }}{{ $activation_date }}
        </div>
        @if($profile->group_id == 6)
            <div class="style-light style-italic">
                Аккаунт:  Модератором {{ $profile->moderator_approve ? 'подтвержден' : 'не подтвержден' }}
            </div>
            <div class="style-light style-italic">
                Статистика:  {{ $profile->statistic ? 'Учитывается в статистике' : 'Не учитывается в статистике' }}
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

        @if($orderListeners = OrderListeners::where('user_id',$profile->id)->get())
        <table class="tech-table sortable purchase-table">
            <thead>
            <tr>
                <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                <th class="sort sort--asc">Заказ <span class="sort--icon"></span> </th>
                <th class="sort sort--asc">Статус <span class="sort--icon"></span> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($orderListeners as $study)
                <tr {{ ($study->start_status == 1 && $study->over_status == 1) ? 'class="finished-course"' : '' }}>
                    <td class="vertical-top">{{ $study->course->code  }}. {{ $study->course->title }}</td>
                    <td class="vertical-top">
                        №{{ getOrderNumber($study->order) }}
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop