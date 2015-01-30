@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php
    $messages = Dictionary::valuesBySlug('system-messages',function($query){
        $query->orderBy('dictionary_values.updated_at','DESC');
        $query->orderBy('dictionary_values.id','DESC');
        $query->filter_by_field('user_id',Auth::user()->id);
    });
    ?>
    <h2>{{ User_individual::where('id',Auth::user()->id)->pluck('fio') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees margin-bottom-40">
            <h3 class="no-margin">Уведомления</h3>
        </div>
        <table class="tech-table sortable notif-table">
            <tbody>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->name }}</td>
                    <td class="vertical-top">{{ $message->updated_at->timezone(Config::get('site.time_zone'))->format('d.m.Y в H:i') }}</td>
                    <td class="equal-padding vertical-top">
                    {{ Form::open(array('url'=>URL::route('individual-notification-delete',array('notification_id'=>$message->id)), 'style'=>'display:inline-block; width: 100%;', 'method'=>'delete')) }}
                        <button type="submit" title="Удалить сообщение" class="icon-bag-btn"></button>
                    {{ Form::close() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop