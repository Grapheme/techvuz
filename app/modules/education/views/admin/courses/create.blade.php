@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h1>Направления и курсы: Добавление курса</h1>
    <h4>Направление обучения &laquo;{{ $direction->title }}&raquo;</h4>
{{ Form::open(array('url'=>URL::route('courses.update',array('directions'=>$direction->id)), 'role'=>'form', 'class'=>'smart-form', 'id'=>'course-form', 'method'=>'post','files'=>TRUE)) }}
	{{ Form::hidden('direction_id',$direction->id) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для добавление нового курса заполните форму:</header>
				<fieldset>
					<section>
						<label class="label">Код</label>
						<label class="input">
							{{ Form::text('code', '') }}
						</label>
					</section>
					<section>
						<label class="label">Название</label>
						<label class="input">
							{{ Form::text('title', '') }}
						</label>
					</section>
                    <section>
						<label class="label">Описание</label>
						<label class="textarea">
							{{ Form::textarea('description', '',array('class'=>'redactor')) }}
						</label>
					</section>
					<section>
                        <label class="label">Цена</label>
                        <label class="input">
                            {{ Form::text('price', '') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Количество часов</label>
                        <label class="input">
                            {{ Form::text('hours', '') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Литература</label>
                        <label class="input">
                            {{ ExtForm::upload('libraries') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Учебный план</label>
                        <label class="input">
                            {{ ExtForm::upload('curriculum') }}
                        </label>
                    </section>
                    <section>
                        <label class="label">Методические материалы</label>
                        <label class="input">
                            {{ ExtForm::upload('metodical') }}
                        </label>
                    </section>
                </fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Создать</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
{{ Form::close() }}
@stop
@section('scripts')
<script>
var essence = 'course';
var essence_name = 'курс';
var validation_rules = {
    direction_id: { required: true },
    code: { required: true },
    title: { required: true },
};
var validation_messages = {
    direction_id: { required: 'Укажите направление обучения' },
    code: { required: 'Укажите код' },
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