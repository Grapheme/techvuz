@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php
$messages = Dictionary::valuesBySlug('system-messages',function($query){
    $query->orderBy('dictionary_values.updated_at','DESC');
    $query->orderBy('dictionary_values.id','DESC');
    $query->filter_by_field('user_id', '=', 0);
});
?>
<h2 class="margin-bottom-40">Уведомления</h2>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @if(count($messages))
        <div class="pull-right">
            <div class="select-payments margin-bottom-10 text-right">
                <a href="javasccript:void(0);" class="font-sm margin-right-10 js-check-all-payments">Выбрать все</a>
                <a href="javasccript:void(0);" class="font-sm js-uncheck-all-payments">Убрать все</a>
            </div>
        </div>
    @endif
    @if(count($messages))
        {{ Form::open(array('url'=>URL::route('moderator-notification-delete',array('notification_id'=>'selected')), 'style'=>'display:inline-block; width: 100%;', 'method'=>'delete')) }}
        {{ Form::submit('Удалить сообщения',array('title'=>'Удалить сообщение','class'=>'btn btn-danger pull-right')) }}
        <table class="tech-table payments-table sortable">
            <tbody>
                <tr>
                    <th class="sort sort--asc">Содержание <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Дата <span class="sort--icon"></span> </th>
                    <th>
                        
                    </th>
                </tr>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}</td>
                    <td>
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
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop