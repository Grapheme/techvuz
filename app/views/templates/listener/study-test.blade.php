@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_listener::where('id',Auth::user()->id)->pluck('fio') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>{{ $test->title }}</h4>
            <div class="desc">
                 по курсу {{ $test->course->code }}. {{ $test->course->title }}
            </div>
            @if(Session::get('message'))
            <div>
                <p>{{ Session::get('message') }}</p>
            </div>
            @endif
        @if($test->questions->count())
            {{ Form::open(array('url'=>URL::route('listener-study-test-finish',array('course_id'=>$study_course->id,'test_id'=>$test->id)), 'method'=>'POST')) }}
                {{ Form::hidden('time_attempt',0) }}
                <ul data-questions-count="{{ $test->questions->count() }}">
            @foreach($test->questions as $question)
                @if($question->answers->count())
                    <li data-question="{{ $question->id }}">
                        <h4>{{ $question->title }}{{ $question->order }}</h4>
                        <div>{{ $question->description }}</div>
                        <ul data-answers-count="{{ $question->answers->count() }}">
                        @foreach($question->answers as $answer)
                            <li>
                                {{ Form::radio('questions['.$question->id.']',$answer->id,NULL,array('data-answer'=>$answer->id,'autocomplete'=>'off')) }}
                                {{ $answer->description }}
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
                    <li data-question="finish">
                        <h4>Результаты тестирования</h4>
                        <div>
                            Вы прошли тестирование, ваш результат:
                            <br> {{ $test->questions->count() }} из {{ $test->questions->count() }}
                        </div>
                        <div>
                            {{ Form::submit('Просмотреть результат') }}
                        </div>
                    </li>
                </ul>
            {{ Form::close() }}
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop