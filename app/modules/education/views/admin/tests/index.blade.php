@extends(Helper::acclayout())
@section('content')
<h1>Направления и курсы: Курсы. </h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
@if(!is_null($chapter))
<h4>Глава &laquo;{{ $chapter->title }}&raquo;. {{ $test->title }}</h4>
<?php $chapter_id = $chapter->id?>
@else
<h4>{{ $test->title }}</h4>
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
    </div>
</div>
<div class="row">

@if($test->questions->count())
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    @foreach($test->questions as $question_index => $question)
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
        <table class="table table-striped table-bordered">
            <tbody>
                @foreach($question->answers as $answer_index => $answer)
                <tr class="vertical-middle">
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
@stop
