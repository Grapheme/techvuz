@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

    {{ Form::open(array('url' => action($module['class'].'@postStore'), 'role'=>'form', 'class'=>'smart-form', 'id'=>'user-form', 'method'=>'post')) }}
	<div class="row">
		<section class="col col-6">
			<div class="well">
				<header>Для создания пользователя заполните форму:</header>
				<fieldset>
					<section>
						<label class="label">Имя</label>
						<label class="input">
							{{ Form::text('name', '') }}
						</label>
						<div class="note">Реальное имя пользователя. Например: Александр</div>
					</section>
					<section>
						<label class="label">Группа пользователя</label>
						<label class="select">
							{{ Form::select('group', $groups_data) }}
						</label>
						<div class="note">От группы зависят права пользователя на сайте.</div>
					</section>
					<section>
						<label class="label">E-mail</label>
						<label class="input">
							{{ Form::text('email', '') }}
						</label>
						<div class="note">Будет использоваться для входа пользователя на сайт. Например: user@mail.ru</div>
					</section>
					<section>
						<label class="label">Пароль</label>
						<label class="input">
							{{ Form::text('password1', '') }}
						</label>
					</section>
					<section>
						<label class="label">Повторите пароль</label>
						<label class="input">
							{{ Form::text('password2', '') }}
						</label>
					</section>
				</fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Создать</span>
					</button>
				</footer>
			</div>
		</section>
	</div>
    {{ Form::close() }}
@stop


@section('scripts')
	<script src="{{ link::to('js/modules/users.js') }}"></script>
	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function'){
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
		}else{
			loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
		}
	</script>
@stop