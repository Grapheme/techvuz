@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
<?php
    $messages = Dictionary::valuesBySlug('system-messages',function($query){
        $query->orderBy('dictionary_values.updated_at','DESC');
        $query->orderBy('dictionary_values.id','DESC');
        $query->filter_by_field('user_id','=',Auth::user()->id);
    });
?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees">
            <h3 class="no-margin">Уведомления</h3>
            @if(count($messages))
            <div class="pull-right">
                <div class="select-payments margin-bottom-10 text-right">
                    <a href="javasccript:void(0);" class="font-sm margin-right-10 js-check-all-payments">Выбрать все</a>
                    <a href="javasccript:void(0);" class="font-sm js-uncheck-all-payments">Убрать все</a>
                </div>
            </div>
            @endif
        </div>
        @if(count($messages))
        {{ Form::open(array('url'=>URL::route('organization-notification-delete',array('notification_id'=>'selected')), 'style'=>'display:inline-block; width: 100%;', 'method'=>'delete')) }}
            {{ Form::submit('Удалить сообщения',array('title'=>'Удалить сообщение','class'=>'btn btn-danger pull-right')) }}
        <table class="tech-table payments-table sortable notif-table">
            <tbody>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->name }}</td>
                    <td class="vertical-top">{{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}</td>
                    <td class="equal-padding vertical-top">
                        {{ Form::checkbox('messages[]',$message->id,NULL,array('class'=>'js-set-listener-access','autocomplete'=>'off')) }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ Form::close() }}
        @else
            <p>Уведомления отсутствуют</p>
        @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop