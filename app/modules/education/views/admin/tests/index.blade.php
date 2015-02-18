@extends(Helper::acclayout())
@section('content')
<h1>Направления и курсы: Курсы. </h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
<h4>Курс {{ $course->code }}. &laquo;{{ $course->title }}&raquo;</h4>
@if(!is_null($chapter))
<h4>
    Глава &laquo;{{ $chapter->title }}&raquo;
    @if(!empty($chapter->test_title)){{ $chapter->test_title }}@else{{ $test->title }}@endif
</h4>
<?php $chapter_id = $chapter->id?>
@else
<h4>@if(!empty($chapter->test_title)){{ $chapter->test_title }}@else{{ $test->title }}@endif</h4>
<?php $chapter_id = 0; ?>
@endif
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('directions.index') }}">Направления обучения</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('courses.index',array('direction'=>$direction->id)) }}">Список курсов</a>
        </div>
        <div class="pull-left margin-right-10">
            <a class="btn btn-default" href="{{ URL::route('modules.index',array('direction'=>$direction->id,'course'=>$course->id)) }}">Модули</a>
        </div>
        <div class="btn-group margin-top-40 margin-right-10">
            {{ Form::open(array('url'=>URL::route('testing.dublicate',array('directions'=>$direction->id,'course_id'=>$course->id,'chapter_id'=>$chapter_id)), 'role'=>'form', 'class'=>'smart-form', 'method'=>'post')) }}
                {{ Form::select('course_id',Courses::orderBy('code')->lists('code','id')) }}
            <?php
            $chapters_select = Chapter::where('id','!=',$chapter_id)->orderBy('order')->select('id','course_id','title')->get();
            ?>
                <select name="chapter_id">
                @foreach($chapters_select as $chapter_select)
                    <option data-course="{{ $chapter_select->course_id }}" value="{{ $chapter_select->id }}">{{ $chapter_select->title }}</option>
                @endforeach
                    <option value="0">Итоговый тест</option>
                </select>
                <button type="submit" autocomplete="off" class="btn btn-success create-dublicate-test">Создать копию</button>
            {{ Form::close() }}
            @if(Session::has('message'))
                <?php $message = Session::get('message'); ?>
                @if(!empty($message))
                <div class="alert alert-info fade in">
                    <i class="fa-fw fa fa-info"></i> {{ Session::get('message') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
<div class="row">
@if($test->questions->count())
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @foreach($test->questions as $question_index => $question)
        <a name="question_{{ $question->id }}"></a>
        <table class="table table-striped table-bordered">
            <tbody>
                <tr class="vertical-middle">
                    <td class="col-lg-1">{{ $question->title }}{{ $question->order }}</td>
                    <td class="col-lg-9">{{ $question->description }}</td>
                    <td class="col-lg-2 text-center">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('questions.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('questions.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id)) }}" style="display:inline-block">
                            <button type="submit" class="btn btn-danger remove-question">Удалить</button>
                        </form>
                        @endif
                    </td>
                <tr>
            </tbody>
        </table>
        @if($question->answers->count())
        <table class="table answers-table table-striped table-bordered">
            <tbody class="sortable" data-question="{{ $question->id  }}">
                @foreach($question->answers as $answer_index => $answer)
                <tr data-id="{{ $answer->id }}" class="vertical-middle">
                    <td class="col-lg-1 text-center">{{ $answer->title }}{{ $answer->order }}</td>
                    <td class="col-lg-8">{{ $answer->description }}</td>
                    <td class="col-lg-1 text-center">{{ $answer->correct ? 'верный' : 'неверный' }}</td>
                    <td class="col-lg-2 text-center" style="white-space:nowrap;">
                        @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('answers.edit',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id,'answer'=>$answer->id)) }}" class="btn btn-success margin-right-10">Изменить</a>
                        @endif
                        @if(Allow::action($module['group'], 'delete'))
                        <form method="DELETE" action="{{ URL::route('answers.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id,'answer'=>$answer->id)) }}" style="display:inline-block">
                            <button type="submit" target="reload" class="btn btn-danger remove-answer">Удалить</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        <table class="table table-striped table-bordered">
            <tbody>
                <tr class="vertical-middle">
                    <td class="col-lg-10">
                    @if(Allow::action($module['group'], 'edit'))
                        <a href="{{ URL::route('answers.create',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id)) }}" class="btn btn-success margin-right-10">Добавить ответ</a>
                    @endif
                        <a href="#" class="btn btn-success margin-right-10 js-show-answers">Показать ответы</a>
                        <a href="#" class="btn btn-success margin-right-10 js-hide-answers" style="display: none;">Скрыть ответы</a>
                    </td>
                    <td class="col-lg-2 text-center"> </td>
                <tr>
            </tbody>
        </table>
    @endforeach
    </div>
@endif
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-25 margin-top-10">
        <div class="pull-left margin-right-10">
            <a class="btn btn-success" href="{{ URL::route('questions.create',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id)) }}">Добавить вопрос</a>
        </div>
        @if(Allow::action($module['group'], 'delete'))
        <div class="pull-left margin-right-10">
            <form method="DELETE" action="{{ URL::route('testing.destroy',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id)) }}" style="display:inline-block">
                <button type="submit" class="btn btn-danger remove-test">Удалить тест</button>
            </form>
        </div>
        @endif
    </div>
</div>
@stop
@section('scripts')
<script>
var essence_test = 'test';
var essence_test_name = 'тест';
var essence_question = 'question';
var essence_question_name = 'вопрос';
var validation_rules = {};
var validation_messages = {};
var essence_answer = 'answer';
var essence_answer_name = 'вопрос';
var validation_rules = {};
var validation_messages = {};
</script>
<script src="{{ url('theme/js/course-test.js') }}"></script>
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
<script>
    $('.js-show-answers').click( function(e){
        e.preventDefault();

        if ($(this).parents('.table').prev().is('.answers-table')){
            $(this).parents('.table').prev().slideDown( 400 );
        }

        $(this).hide();
        $(this).parents('.table').find('.js-hide-answers').show();
    });
    $('.js-hide-answers').click( function(e){
        e.preventDefault();

        if ($(this).parents('.table').prev().is('.answers-table')){
            $(this).parents('.table').prev().slideUp( 400 );
        }

        $(this).hide();
        $(this).parents('.table').find('.js-show-answers').show();
    });
    $(document).on("mouseover", ".sortable", function(e){
        if ( !$(this).data('sortable') ) {
            $(this).sortable({
                stop: function() {
                    var pls = $(this).find('tr');
                    var poss = [];
                    var question = $(this).data('question');
                    $(pls).each(function(i, item) {
                        poss.push($(item).data('id'));
                    });
                    $.ajax({
                        url: "{{ URL::route('testing.order',array('direction'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id)) }}",
                        type: "post",
                        data: {poss: poss, question:question},
                        success: function() {}
                    });
                }
            });
        }
    });
</script>
@stop
