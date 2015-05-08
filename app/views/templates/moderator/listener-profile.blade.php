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
        <a class="icon--blue pull-right margin-top-30 style-normal" href="{{ URL::route('moderator-listener-profile-edit',$profile->id) }}">
            <span class="icon icon-red"></span> Редактировать
        </a>
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
                        <a href="{{ URL::route('moderator-order-extended',$study->order->id) }}"> №{{ getOrderNumber($study->order) }}</a>
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
                            <a class="style-normal nowrap" href="{{ URL::route('moderator-order-certificate',array('order_id'=>$study->order_id,'course_id'=>$study->id,'listener_id'=>$study->user_id,'format'=>'pdf')) }}">
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
    @if($profile->group_id == 6)
        @if($orders = Orders::where('user_id',$profile->id)->with('payment')->with('listeners')->get())
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
    @endif
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop