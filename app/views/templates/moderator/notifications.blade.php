@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php
$messages = Dictionary::valuesBySlug('system-messages',function($query){
    $query->orderBy('dictionary_values.updated_at','DESC');
    $query->orderBy('dictionary_values.id','DESC');
    $query->filter_by_field('user_id',0);
});
?>
<h2 class="margin-bottom-40">Уведомления</h2>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="tech-table sortable">
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
                    {{ Form::open(array('url'=>URL::route('moderator-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block', 'method'=>'delete')) }}
                        
                        <button type="submit" class="icon-bag-btn" title="Удалить"></button>

                    {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop