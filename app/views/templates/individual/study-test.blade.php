<?php
$isChapterTest = $test->chapter_id ? TRUE : FALSE;
?>
@extends(Helper::acclayout())
@section('style')
    @if($isChapterTest)
        <style type="text/css">

            .state-success {
                background: #f0fff0 none repeat scroll 0 0;
                border-color: #7dc27d;
            }

            .state-error {
                background: #fff0f0 none repeat scroll 0 0;
                border-color: #a90329;
            }
        </style>
    @endif
@stop
@section('content')
    <main class="cabinet">
        <a class="name-dashboard" href="{{ URL::route('dashboard') }}">
            <h1>{{ User_individual::where('id',Auth::user()->id)->pluck('fio'); }}</h1></a>

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
                    <ul class="questions-ul" data-questions-count="{{ $test->questions->count() }}">
                        @foreach($test->questions as $index => $question)
                            @if($question->answers->count())
                                <li class="js-question questions-li margin-bottom-30{{ $isChapterTest && $index > 0 ? ' hidden' : '' }}"
                                    data-question="{{ $question->id }}">
                                    <h4>{{ $question->title }}{{ $question->order }}</h4>

                                    <div>{{ $question->description }}</div>
                                    <ul class="answers-ul" data-answers-count="{{ $question->answers->count() }}">
                                        @foreach($question->answers as $answer)
                                            <li class="answers-li">
                                                <label>
                                                    @if($isChapterTest)
                                                        {{ Form::radio('questions['.$question->id.']',$answer->id,NULL,array('data-answer'=>$answer->id, 'data-current'=> $answer->correct,'autocomplete'=>'off')) }}
                                                    @else
                                                        {{ Form::radio('questions['.$question->id.']',$answer->id,NULL,array('data-answer'=>$answer->id,'autocomplete'=>'off')) }}
                                                    @endif
                                                    {{ $answer->description }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @if($isChapterTest)
                                        <div class="js-question-comment hidden">{{ $question->comment }}</div>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                        @if($isChapterTest)
                            <li class="finish js-finish-question hidden">
                                <div>
                                    <button type="submit" class="margin-top-10 btn btn--bordered btn--blue">Посмотреть
                                        результат
                                    </button>
                                </div>
                            </li>
                            <li class="js-submit-question">
                                <div>
                                    <button type="button" class="margin-top-10 btn btn--bordered btn--blue">Ответить
                                    </button>
                                </div>
                            </li>
                            <li class="js-next-question hidden">
                                <div>
                                    <button type="button" class="margin-top-10 btn btn--bordered btn--blue">Следующий
                                        вопрос
                                    </button>
                                </div>
                            </li>
                        @else
                            <li data-question="finish">
                                <div>
                                    <button type="submit" class="margin-top-10 btn btn--bordered btn--blue">Посмотреть
                                        результат
                                    </button>
                                </div>
                            </li>
                        @endif
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
    @if($isChapterTest)
        <script type="application/javascript">
            $(function () {
                var Test = Test || {};
                var current_question = 0;
                var success_test_percent = {{ Config::get('site.success_test_percent') }};
                var success_course_test = '<p>Поздравляем, Вы прошли пробное тестирование.</p> <h4>Ваш результат: ';
                var fail_course_test = '<p>Вы не прошли тест. Попробуйте снова.</p> <h4>Ваш результат: ';
                $(".questions-li").each(function (index) {
                    Test[index] = {
                        question: $(this).data('question'),
                        right_answers: $(this).find(".answers-ul input:checkbox[data-current='1']").length,
                        user_right_answers: 0
                    };
                });
                $(".js-submit-question button").click(function () {
                    var question_id = $(".questions-li:visible").data('question');
                    $(".questions-li:visible input:radio").removeClass('disabled').removeAttr('disabled').parent('label').removeClass('state-success state-error');
                    if ($(".questions-li:visible input:radio:checked").length == 0) {
                        $(this).after('<p style="margin: 20px 0 0 15px; position: absolute;  display:inline-block;">Отметьте правильный вариант</p>').next().fadeOut(1500);
                    } else {
                        $(".questions-li:visible input:radio[data-current='1']").parent('label').addClass('state-success');
                        $(".questions-li:visible input:radio:checked[data-current='0']").parent('label').addClass('state-error');
                        $(".questions-li:visible input:radio").addClass('disabled').attr('disabled', 'disabled');
                        if ($(".questions-li:visible input:radio:checked[data-current='0']").length == 0) {
                            Test[current_question]['user_right_answers'] = $(".questions-li:visible input:radio:checked[data-current='1']").length;
                        } else {
                            Test[current_question]['user_right_answers'] = 0;
                        }
                        $(".questions-li:visible .js-question-comment").removeClass('hidden');
                        $(".js-submit-question").addClass('hidden');
                        current_question++;
                        if (current_question == countOfObject(Test)) {
                            $(".js-finish-question").removeClass('hidden');
                        } else {
                            $(".js-next-question").removeClass('hidden');
                        }
                    }
                });
                $(".js-next-question").click(function () {
                    $(this).addClass('hidden');
                    $(".js-submit-question").removeClass('hidden');
                    $(".questions-li").addClass('hidden');
                    $(".questions-li[data-question='" + Test[current_question]['question'] + "']").removeClass('hidden');
                });
                $(".js-finish-question").click(function () {
                    $(".questions-li input:radio").removeClass('disabled').removeAttr('disabled');
                    $(this).addClass('disabled');
                });
                function countOfObject(obj) {
                    var t = typeof(obj);
                    var i = 0;
                    if (t != "object" || obj == null) return 0;
                    for (x in obj) i++;
                    return i;
                }
            });
        </script>
    @else
        <script type="application/javascript">
            questions_hide();
        </script>
    @endif
@stop