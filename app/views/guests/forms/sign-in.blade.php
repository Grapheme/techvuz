{{ Form::open(array('route'=>'signin','role'=>'form','class'=>'smart-form client-form','id'=>'signin-form')) }}
	<header>Вы уже зарегистрированы? Войдите:</header>
	<fieldset>
		<section>
			<label class="label">E-mail</label>
			<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
				<input type="email" name="login">
				<b class="tooltip tooltip-top-right"><i class="fa fa-envelope-o txt-color-teal"></i> Введите Email адрес</b>
			</label>
		</section>
		<section>
			<label class="label">Пароль</label>
			<label class="input"> <i class="icon-append fa fa-lock"></i>
				<input type="password" name="password">
				<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Введите пароль</b>
			</label>
{{--
			<div class="note">
				<a href="javascript:void(0)">Забыли пароль?</a>
			</div>
--}}
		</section>
		<section>
			<label class="checkbox">
				<input type="checkbox" name="remember" value="1" checked="">
				<i></i>Запомнить меня
			</label>
		</section>
	</fieldset>
	<footer>
		<button type="submit" autocomplete="off" class="btn btn-primary no-margin regular-10 uppercase">
			<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Вход</span>
		</button>
	</footer>
{{ Form::close() }}