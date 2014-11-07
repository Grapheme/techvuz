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
    <h2>{{ User_organization::where('id',Auth::user()->id)->pluck('title') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Уведомления</h3>
        </div>
        <table class="tech-table sortable">
            <tbody>
                <tr>
                    <th class="sort sort--asc">Содержание <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Дата <span class="sort--icon"></span> </th>
                </tr>
            @foreach($messages as $message)
                <tr>
                    <td>{{ $message->name }}</td>
                    <td>{{ $message->updated_at->format('d.m.Y в H:i') }}</td>
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