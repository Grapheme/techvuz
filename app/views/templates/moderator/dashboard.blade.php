@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php
$orders = Orders::where('payment_status',1)->orderBy('created_at','DESC')->limit(3)->with('payment','listeners','payment_numbers')->get();
$messages = Dictionary::valuesBySlug('system-messages',function($query){
    $last14Days = \Carbon\Carbon::now()->subDays(14);
    $query->orderBy('dictionary_values.updated_at','DESC');
    $query->orderBy('dictionary_values.id','DESC');
    $query->where('dictionary_values.updated_at','>=',$last14Days);
    $query->filter_by_field('user_id',0);
});
?>
<div class="cabinet-tabs">
    @if($messages->count())
    <div>
        <h3>Уведомления</h3>
        <div class="notifications">
            <div class="notifications-nav">
                <span class="icon icon-angle-left js-notif-left"></span>
                <span class="notifications-count">
                    <span class="current">1</span> / <span class="all"></span>
                </span>
                <span class="icon icon-angle-right js-notif-right">
                    <a href="{{ URL::route('moderator-notifications') }}" class="btn btn--bordered btn--blue">
                        Полный список
                    </a>
                </span>
            </div>
            <ul class="notifications-ul">
            @foreach($messages as $index => $message)
                <li class="notifications-li container-fluid">
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <div class="notif-type">
                                Системное сообщение {{ $index+1 }}
                            </div>
                            <div class="notif-cont">
                                {{ $message->name }}
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <div class="notif-date font-sm">
                                {{ $message->updated_at->timezone('Europe/Moscow')->format('d.m.Y в H:i') }}
                            </div>
                            <div class="notif-delete js-notif-delete">
                            {{ Form::open(array('url'=>URL::route('moderator-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                                {{ Form::submit('удалить',array('title'=>'Удалить сообщение')) }}
                            {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif
    @if($orders->count())
    <div>
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
    @endif
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop