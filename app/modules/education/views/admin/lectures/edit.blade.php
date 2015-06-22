@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h4 class="bigger-register">Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
    <h4 class="bigger-register">Курс {{ $course->code }} &laquo;{{ $course->title }}&raquo;</h4>
    <h4 class="bigger-register">Глава &laquo;{{ $chapter->title }}&raquo;</h4>
    <h4 class="bigger-register">Редактирование модуля</h4>
{{ Form::model($lecture, array('url'=>URL::route('lectures.update',array('directions'=>$direction->id,'course'=>$course->id,'chapter'=>$chapter->id,'lecture'=>$lecture->id)), 'class'=>'smart-form', 'id'=>'lecture-form', 'role'=>'form', 'method'=>'PUT', 'files'=>true)) }}
	{{ Form::hidden('course_id',$course->id) }}
	{{ Form::hidden('chapter_id',$chapter->id) }}
	{{ Form::hidden('order',$lecture->order) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
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
                        <label class="label">Количество часов</label>
                        <label class="input">
                            {{ Form::text('hours') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Документ</label>
                        <label class="input">
                            {{ ExtForm::upload('document', $lecture->document) }}
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
var essence = 'lecture';
var essence_name = 'лекция';
var validation_rules = {
    course_id: { required: true },
    chapter_id: { required: true },
    title: { required: true },
};
var validation_messages = {
    course_id: { required: 'Укажите курс' },
    chapter_id: { required: 'Укажите главу' },
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