@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
<h1>Направления и курсы: Добавление курса</h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
@if(!is_null($chapter))
<h4>Глава &laquo;{{ $chapter->title }}&raquo;. {{ $test->title }}</h4>
<?php $chapter_id = $chapter->id?>
@else
<h4>{{ $test->title }}</h4>
<?php $chapter_id = 0; ?>
@endif
<p>Вопрос №{{ $question->order }}</p>
<p>{{ $question->description }}</p>
{{ Form::model($answer, array('url'=>URL::route('answers.update',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter_id,'test'=>$test->id,'question'=>$question->id,'answer'=>$answer->id)), 'class'=>'smart-form', 'id'=>'answer-form', 'role'=>'form', 'method'=>'PUT')) }}
	{{ Form::hidden('test_id',$test->id) }}
    	{{ Form::hidden('test_question_id') }}
    	{{ Form::hidden('order',$answer->order) }}
    	{{ Form::hidden('title') }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для изменения ответа отредактируйте форму:</header>
				<fieldset>
                    <section>
                        <label class="label">Ответ</label>
                        <label class="textarea">
                            {{ Form::textarea('description',NULL,array('class'=>'redactor')) }}
                        </label>
                    </section>
                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('correct',1) }}
                            <i></i>Установите если ответ является верным
                        </label>
                    </section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ URL::previous() }}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop
@section('scripts')
<script>
var essence = 'answer';
var essence_name = 'ответ';
var validation_rules = {
    test_id: { required: true },
    test_question_id: { required: true },
    title: { required: true },
};
var validation_messages = {
    test_id: { required: 'Укажите тест' },
    test_question_id: { required: 'Укажите вопрос' },
    title: { required: 'Укажите название' },
};
</script>
{{ HTML::script('js/modules/standard.js') }}
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
{{ HTML::script('js/vendor/redactor.min.js') }}
{{ HTML::script('js/system/redactor-config.js') }}
@stop