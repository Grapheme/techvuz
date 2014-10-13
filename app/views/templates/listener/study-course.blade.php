@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_listener::where('id',Auth::user()->id)->first()->fio }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>{{ $module->code }}. {{ $module->title }}</h4>
            <div class="desc">
                {{ $module->description }}
            </div>
        @if($module->chapters->count())
            <div>
                <table class="tech-table sortable">
                    <tbody>
                        <tr>
                            <th>№</th>
                            <th>Название</th>
                            <th>Форма контроля</th>
                        </tr>
                    @foreach($module->chapters as $chapter)
                        <tr>
                            <td>{{ $chapter->order }}</td>
                            <td>{{ $chapter->title }}</td>
                            <td>Тестирование</td>
                        </tr>
                        @if($chapter->lectures->count())
                            @foreach($chapter->lectures as $lecture)
                            <tr>
                                <td>{{ $chapter->order }}.{{ $lecture->order }}</td>
                                <td>{{ $lecture->title }}</td>
                                <td>
                                    {{ Form::open(array('url'=>URL::route('listener-study-download-lecture',array('study_course_id'=>$study_course->id,'lecture_id'=>$lecture->id)), 'style'=>'display:inline-block', 'method'=>'POST')) }}
                                        {{ Form::submit('Скачать',array('class'=>'btn btn-link')) }}
                                    {{ Form::close() }}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop