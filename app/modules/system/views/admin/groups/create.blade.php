@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

    {{ Form::open(array('url' => action($module['class'].'@postStore'), 'role'=>'form', 'class'=>'smart-form', 'id'=>'group-form', 'method'=>'post')) }}
	<div class="row margin-top-10">
		<section class="col col-6">
			<div class="well">
				<header>Для создания группы пользователей заполните форму:</header>
				<fieldset>
					<section>
						<label class="label">Системное название</label>
						<label class="input">
							{{ Form::text('name', '') }}
						</label>
						<div class="note">Только латинские символы, без пробелов. Например: admin</div>
					</section>
					<section>
						<label class="label">Описание</label>
						<label class="input">
							{{ Form::text('desc', '') }}
						</label>
						<div class="note">Описание группы. Например: Администраторы</div>
					</section>
					<section>
						<label class="label">Базовый шаблон</label>
						<label class="input">
							{{ Form::text('dashboard', '') }}
						</label>
						<div class="note">Наименование используемого базового шаблона. Например: default</div>
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
	<script src="{{ link::to('js/modules/groups.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
		}
	</script>
@stop