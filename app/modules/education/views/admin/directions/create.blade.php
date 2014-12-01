@extends(Helper::acclayout())
@section('style')
{{ HTML::style('css/redactor.css') }}
@stop
@section('content')
    <h1>Направления и курсы: Новое направление обучения</h1>
{{ Form::open(array('url'=>URL::route('directions.store'), 'role'=>'form', 'class'=>'smart-form', 'id'=>'direction-form', 'method'=>'post')) }}
	{{ Form::hidden('order',(int) DB::table('directions')->max('order')+1) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для добавление нового направления заполните форму:</header>
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
						<label class="label">Скидка, %</label>
						<label class="input">
							{{ Form::text('discount',0) }}
						</label>
					</section>
					<section>
						<label class="label">Логотип</label>
						<label class="input">
						    {{ ExtForm::image('photo_id') }}
						</label>
					</section>
					<section>
					    <label class="checkbox">
                            {{ Form::checkbox('active',1,TRUE) }}
                            <i></i>Доступен пользователям
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
var essence = 'direction';
var essence_name = 'направление';
var validation_rules = {
    code: { required: true },
    title: { required: true },
};
var validation_messages = {
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
{{ HTML::script('js/vendor/redactor.js') }}
{{ HTML::script('js/system/redactor-config.js') }}
@stop