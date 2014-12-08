@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h1>Направления и курсы: Редактирование курса</h1>
    <h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
    <h4>Курс &laquo;{{ $course->title }}&raquo;</h4>
{{ Form::model($chapter, array('url'=>URL::route('chapters.update',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id)), 'class'=>'smart-form', 'id'=>'chapter-form', 'role'=>'form', 'method'=>'PUT')) }}
	{{ Form::hidden('course_id',$course->id) }}
	{{ Form::hidden('order',$chapter->order) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для изменения главы отредактируйте форму:</header>
				<fieldset>
                    <section>
                        <label class="label">Название</label>
                        <label class="input">
                            {{ Form::text('title') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Описание</label>
                        <label class="textarea">
                            {{ Form::textarea('description',NULL,array('class'=>'redactor')) }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Название промежуточной аттестации</label>
                        <label class="input">
                            {{ Form::text('test_title') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Количество часов</label>
                        <label class="input">
                            {{ Form::text('hours') }}
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
var essence = 'chapter';
var essence_name = 'глава';
var validation_rules = {
    course_id: { required: true },
    title: { required: true },
};
var validation_messages = {
    course_id: { required: 'Укажите курс' },
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
{{ HTML::script('js/vendor/redactor.js') }}
{{ HTML::script('js/system/redactor-config.js') }}
@stop