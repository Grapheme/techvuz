@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php
$orders = Orders::where('payment_status',1)->orderBy('created_at','DESC')->limit(3)->with('payment')->with('listeners')->get();
?>
<div class="cabinet-tabs">
    <h3>Уведомления</h3>
    <div class="notifications">
        <div class="notifications-nav">
            <span class="icon icon-angle-left js-notif-left"></span>
            <span class="notifications-count">
                <span class="current">1</span> / <span class="all"></span>
            </span>
            <span class="icon icon-angle-right js-notif-right"></span>
        </div>
        <ul class="notifications-ul">
        @for($i=0;$i<19;$i++)
            <li class="notifications-li container-fluid">
                <div class="row">
                    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                        <div class="notif-type">
                            Системное сообщение {{ $i+1 }}
                        </div>
                        <div class="notif-cont">
                            Заказ №400 не оплачен, но доступ к обучению предоставлен.
                        </div>
                        <div class="margin-top-20">
                            <button class="btn btn--bordered btn--blue">
                                Загрузить счет
                            </button>
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                        <div class="notif-date font-sm">
                            24.09.14
                        </div>
                        <div class="notif-delete js-notif-delete">
                            удалить
                        </div>
                    </div>
                </div>
            </li>
        @endfor
        </ul>
    </div>
    <h3>Последние не оплаченные заказы</h3>
    <ul class="orders-ul">
    <?php $showed = 0; $maxCourses = 3; ?>
    @foreach($orders as $order)
        @if($showed >= $maxCourses)
            <?php break; ?>
        @endif
        @include(Helper::acclayout('assets.order'))
        <?php $showed++; ?>
    @endforeach
    </ul>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop