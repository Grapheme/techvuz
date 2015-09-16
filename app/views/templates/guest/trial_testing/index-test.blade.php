@section('title'){{ $course_seo->title }}@stop
@section('description'){{ $course_seo->description }}@stop
@section('keywords'){{ $course_seo->keywords }}@stop

@extends(Helper::layout())
@section('style')
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
@stop
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
                <ul class="questions-ul" data-questions-count="{{ $test->questions->count() }}">
                    @foreach($test->questions as $index => $question)
                        @if($question->answers->count())
                            <li class="js-question questions-li margin-bottom-30{{ $index > 0 ? ' hidden' : '' }}"
                                data-question="{{ $question->id }}">
                                <h4>{{ $question->title }}{{ $question->order }}</h4>

                                <div>{{ $question->description }}</div>
                                <ul class="answers-ul" data-answers-count="{{ $question->answers->count() }}">
                                    @foreach($question->answers as $answer)
                                        <li class="answers-li">
                                            <label>
                                                {{ Form::checkbox('questions['.$question->id.']', $answer->id, NULL, array('data-answer'=>$answer->id, 'data-current'=> $answer->correct, 'autocomplete'=>'off')) }}
                                                {{ $answer->description }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="js-question-comment hidden">{{ $question->comment }}</div>
                            </li>
                        @endif
                    @endforeach
                    <li class="js-test-results hidden">
                        <div class="margin-top-20 margin-bottom-20 js-test-result-text"></div>
                        <div class="margin-bottom-20">
                            Требуемый результат: {{ Config::get('site.success_test_percent') }}%
                        </div>
                        <div class="js-fail-course-test hidden">
                            <button type="button" class="btn btn--bordered btn--blue js-test-reset">Новая попытка
                            </button>
                        </div>
                        <div class="js-success-course-test hidden">
                            @if(Auth::guest())
                                <a class="btn-request" href="{{ pageurl('registration') }}">Оформить заявку</a>
                            @elseif(isOrganizationORIndividual())
                                <div class="new-order-holder">
                                    <a href="{{ URL::route('ordering-select-courses') }}#{{ $course->id }}"
                                       class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
                                </div>
                            @endif
                        </div>
                    </li>
                    <li class="js-finish-question hidden">
                        <div>
                            <button type="submit" class="margin-top-10 btn btn--bordered btn--blue">Посмотреть результат
                            </button>
                        </div>
                    </li>
                    <li class="js-submit-question">
                        <div>
                            <button type="button" class="margin-top-10 btn btn--bordered btn--blue">Ответить</button>
                        </div>
                    </li>
                    <li class="js-next-question hidden">
                        <div>
                            <button type="button" class="margin-top-10 btn btn--bordered btn--blue">Следующий вопрос
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        @endif
    </main>
@stop
@section('scripts')
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
                    right_answers: $(this).find(".answers-ul input:radio[data-current='1']").length,
                    user_right_answers: 0
                };
            });
            $(".js-submit-question button").click(function () {
                var question_id = $(".questions-li:visible").data('question');
                $(".questions-li:visible input:radio").removeClass('disabled').removeAttr('disabled').parent('label').removeClass('state-success state-error');
                if ($(".questions-li:visible input:radio:checked").length == 0) {
                    $(this).after('<p style="margin: 20px 0 0 15px; position: absolute;  display:inline-block;">Отметьте правильные варианты ответов.</p>').next().fadeOut(1500);
                } else {
                    $(".questions-li:visible input:radio[data-current='1']").parent('label').addClass('state-success');
                    $(".questions-li:visible input:radio:checked[data-current='0']").parent('label').addClass('state-error');
                    $(".questions-li:visible input:radio").addClass('disabled').attr('disabled', 'disabled');
                    if($(".questions-li:visible input:radio:checked[data-current='0']").length == 0){
                        Test[current_question]['user_right_answers'] = $(".questions-li:visible input:radio:checked[data-current='1']").length;
                    }else{
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
                $(this).addClass('hidden');
                $(".questions-li").addClass('hidden');
                $(".js-test-result-text").html('');
                var right_answers = 0;
                var user_right_answers = 0;
                $.each(Test, function (index, value) {
                    right_answers += value['right_answers'];
                    user_right_answers += value['user_right_answers'];
                });
                var result_attempt = Number(user_right_answers / right_answers).toPrecision(2) * 100;
                if (result_attempt >= success_test_percent) {
                    $(".js-test-result-text").html(success_course_test + ' ' + result_attempt + '%');
                    $(".js-success-course-test").removeClass('hidden');
                } else {
                    $(".js-test-result-text").html(fail_course_test + ' ' + result_attempt + '%');
                    $(".js-fail-course-test").removeClass('hidden');
                }
                $(".js-test-results").removeClass('hidden');
            });
            $(".js-test-reset").click(function(){
                window.location.reload();
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
@stop