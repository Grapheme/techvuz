{{ Form::open(array('route'=>'signup','role'=>'form','class'=>'smart-form client-form','id'=>'signup-form')) }}
	<header>Создать учетную запись. Это бесплатно!</header>
	<fieldset>
		<section>
			<label class="label">Имя</label>
			<label class="input"> <i class="icon-append fa fa-user"></i>
				<input type="text" name="name">
				<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Введите имя</b>
			</label>
		</section>
		<section>
			<label class="label">Фамилия</label>
			<label class="input"> <i class="icon-append fa fa-user"></i>
				<input type="text" name="surname">
				<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Введите фамилию</b>
			</label>
		</section>
		<section>
			<label class="label">E-mail</label>
			<label class="input"> <i class="icon-append fa fa-envelope-o"></i>
				<input type="email" name="email">
				<b class="tooltip tooltip-top-right"><i class="fa fa-envelope-o txt-color-teal"></i> Введите Email адрес</b>
			</label>
		</section>
		<section>
			<label class="label">Password</label>
			<label class="input"> <i class="icon-append fa fa-lock"></i>
				<input type="password" name="password">
				<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Введите пароль</b>
			</label>
		</section>
		<section>
			<p class="clickedit">
				Нажав на кнопку "Создать аккаунт", вы подтверждаете, что ознакомились и согласны с нашими 
				<a target="_blank" title="" href="javascript:void(0)">условиями использования</a> и 
				<a target="_blank" title="Privacy policy" href="javascript:void(0)">политикой конфиденциальности</a>.
			</p>
		</section>
	</fieldset>
	<footer>
		<button type="submit" autocomplete="off" class="btn btn-primary no-margin regular-10 uppercase">
			<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Создать аккаунт</span>
		</button>
	</footer>
{{ Form::close() }}