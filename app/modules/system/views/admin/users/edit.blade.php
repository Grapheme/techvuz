@extends(Helper::acclayout())


@section('content')

    @include($module['tpl'].'menu')

	<div class="row">
        {{ Form::model($user, array('url' => action($module['class'].'@postUpdate', array('user_id' => $user->id)), 'role'=>'form', 'class'=>'smart-form', 'id'=>'user-form', 'method'=>'post')) }}
		<section class="col col-6">
			<div class="well">
				<header>Для изменения данных пользователя заполните форму:</header>
				<fieldset>
					<section>
						<label class="label">Имя</label>
						<label class="input">
							{{ Form::text('name', NULL) }}
						</label>
					</section>
					<section>
						<label class="label">Фамилия</label>
						<label class="input">
							{{ Form::text('surname', NULL) }}
						</label>
					</section>
					<section>
						<label class="label">E-mail</label>
						<label class="input">
							{{ Form::text('email', NULL) }}
						</label>
					</section>

					<section>
						<label class="checkbox">
							{{ Form::checkbox('active', NULL) }} Активный
                            <i></i>
						</label>
					</section>

					<section>
						<label class="label">Группа пользователя</label>
						<label class="select">
							{{ Form::select('group_id', $groups_data, NULL) }}
						</label>
					</section>

				</fieldset>
				<footer>
					<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{URL::previous()}}">
						<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
					</a>
					<button autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
					</button>
				</footer>
			</div>
		</section>
        {{ Form::close() }}

        {{ Form::model($user, array('url' => action($module['class'].'@postChangepass', array('user_id' => $user->id)), 'role'=>'form', 'class'=>'smart-form', 'id'=>'user-changepass-form', 'method'=>'post')) }}
		<section class="col col-6">
			<div class="well">
				<header>Сменить пароль:</header>
				<fieldset>
					<section>
						<label class="label">Новый пароль</label>
						<label class="input">
							{{ Form::text('password1', '') }}
						</label>
					</section>
					<section>
						<label class="label">Повторите новый пароль</label>
						<label class="input">
							{{ Form::text('password2', '') }}
						</label>
					</section>
				</fieldset>
				<footer>
					<button autocomplete="off" class="btn btn-warning no-margin regular-10 uppercase btn-form-submit">
						<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сменить пароль</span>
					</button>
				</footer>
			</div>
		</section>
        {{ Form::close() }}

	</div>
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