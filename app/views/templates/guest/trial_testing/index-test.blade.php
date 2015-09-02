@section('title'){{ $course_seo->title }}@stop
@section('description'){{ $course_seo->description }}@stop
@section('keywords'){{ $course_seo->keywords }}@stop

@extends(Helper::layout())
@section('style')@stop
@section('content')
    <main class="contacts">
        <h1>{{ $test->title }}</h1>
        <div class="desc">
            по курсу "{{ $course->title }}"
        </div>

        <div class="desc">
            @if(Session::get('message'))
                <div>
                    <p>{{ Session::get('message') }}</p>
                </div>
            @endif
        </div>
        @if($test->questions->count())
            <div class="cabinet-tabs">
                {{ Form::open(array('url'=>URL::route('trial-test-finish', $course_seo->url), 'method'=>'POST')) }}
                {{ Form::hidden('time_attempt',0) }}
                <ul class="questions-ul" data-questions-count="{{ $test->questions->count() }}">
                    @foreach($test->questions as $question)
                        @if($question->answers->count())
                            <li class="js-question questions-li margin-bottom-30" data-question="{{ $question->id }}">
                                <h4>{{ $question->title }}{{ $question->order }}</h4>

                                <div>{{ $question->description }}</div>
                                <ul class="answers-ul" data-answers-count="{{ $question->answers->count() }}">
                                    @foreach($question->answers as $answer)
                                        <li class="answers-li">
                                            <label>
                                                {{ Form::radio('questions['.$question->id.']',$answer->id,NULL,array('data-answer'=>$answer->id,'autocomplete'=>'off')) }}
                                                {{ $answer->description }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endforeach
                    <li data-question="finish">
                        <div>
                            <button type="submit" class="margin-top-10 btn btn--bordered btn--blue">Посмотреть результат
                            </button>
                        </div>
                    </li>
                </ul>
                {{ Form::close() }}
            </div>
        @endif
    </main>
@stop
@section('scripts')
@stop