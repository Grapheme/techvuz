@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <h1>{{ $account->fio }}</h1>
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>@if(!empty($test->course->test_title)){{ $test->course->test_title }}@else{{ $test->title }}@endif</h4>
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
                <ul class="questions-ul" data-questions-count="{{ $test->questions->count() }}">
            @foreach($test->questions as $question)
                @if($question->answers->count())
                    <li class="questions-li margin-bottom-30" data-question="{{ $question->id }}">
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
                        <!-- <h4>Результаты тестирования</h4>
                        <div class="margin-top-20 margin-bottom-20">
                            Вы прошли тестирование, ваш результат:
                            <br> {{ $test->questions->count() }} из {{ $test->questions->count() }}
                        </div> -->
                        <div>
                            <button type="submit" class="margin-top-10 btn btn--bordered btn--blue">Посмотреть результат</button>
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