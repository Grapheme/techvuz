@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php
$orders = Orders::where('payment_status',1)->orderBy('created_at','DESC')->limit(3)->with('payment','listeners','payment_numbers','organization','individual')->get();
$dashboardNotificationBlockTargetValue = Config::get('site.user_setting.dashboard-target-notification-block') ? Config::get('site.user_setting.dashboard-target-notification-block') : 0;
$messages = Dictionary::valuesBySlug('system-messages',function($query){
    $setDate = Config::get('site.user_setting.dashboard-target-notification-block-date') ? Config::get('site.user_setting.dashboard-target-notification-block-date') : \Carbon\Carbon::now()->subDays(14);
    $query->orderBy('dictionary_values.updated_at','DESC');
    $query->orderBy('dictionary_values.id','DESC');
    $query->where('dictionary_values.updated_at','>=',$setDate);
    $query->filter_by_field('user_id',0);
});
if($messages->count()):
    (new AccountsOperationController())->saveUserSetting('dashboard-target-notification-block',0,FALSE);
    $dashboardNotificationBlockTargetValue = 1;
endif;
?>
<div class="cabinet-tabs">
    @if($messages->count())
    <div {{ $dashboardNotificationBlockTargetValue ? '' : 'class="hidden"' }}>
        <h3>Уведомления</h3>
        <div class="notifications">
            <div class="notifications-nav">
                <span class="icon icon-angle-left js-notif-left"></span>
                <span class="notifications-count">
                    <span class="current">1</span> / <span class="all"></span>
                </span>
                <span class="icon icon-angle-right js-notif-right"></span>
                <a href="{{ URL::route('moderator-notifications') }}" class="all-notifications">
                    Полный список
                </a>
                <span>
                    <a data-action="{{ URL::route('setting-update',array('setting_slug'=>'dashboard-target-notification-block','value'=>$dashboardNotificationBlockTargetValue)) }}" class="white-link pull-right js-close-notifications">закрыть</a>
                </span>
            </div>
            <ul class="notifications-ul">
            @foreach($messages as $index => $message)
                <li class="notifications-li container-fluid">
                    <div class="row">
                        <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                            <!-- <div class="notif-type">
                                Системное сообщение {{ $index+1 }}
                            </div> -->
                            <div class="notif-cont">
                                {{ $message->name }}
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            <div class="notif-date font-sm">
                                {{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}
                            </div>
                            <div class="notif-delete js-notif-delete">
                            {{ Form::open(array('url'=>URL::route('moderator-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                                
                                <button type="submit" class="icon-bag-btn" title="Удалить"></button>

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
        <h3>Новые заказы</h3>
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