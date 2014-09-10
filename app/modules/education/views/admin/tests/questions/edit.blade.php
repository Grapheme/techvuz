@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
<h1>Направления и курсы: Добавление курса</h1>
<h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
@if(!is_null($chapter))
<h4>Глава &laquo;{{ $chapter->title }}&raquo;. {{ $test->title }}</h4>
@else
<h4>{{ $test->title }}</h4>
@endif
{{ Form::model($question, array('url'=>URL::route('questions.update',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'test'=>$test->id,'question'=>$question->id)), 'class'=>'smart-form', 'id'=>'question-form', 'role'=>'form', 'method'=>'PUT')) }}
	{{ Form::hidden('test_id') }}
    {{ Form::hidden('title') }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для изменения вопроса отредактируйте форму:</header>
				<fieldset>
                    <section>
                        <label class="label">Вопрос</label>
                        <label class="textarea">
                            {{ Form::textarea('description',NULL,array('class'=>'redactor')) }}
                        </label>
                    </section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ URL::previous() }}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Изменить</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop
@section('scripts')
<script>
var essence = 'question';
var essence_name = 'вопрос';
var validation_rules = {
    test_id: { required: true },
    title: { required: true },
};
var validation_messages = {
    test_id: { required: 'Укажите тест' },
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