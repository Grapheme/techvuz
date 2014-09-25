<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
			Добро пожаловать в {{ HTML::link('','ТехВУЗ.рф') }}.<br>
			Активируйте свой аккаунт, перейдя по <a href="{{ URL::route('signup-activation',array('activate_code'=>User::find($account->id)->temporary_code)) }}">ссылке</a>.<br>
			Не откладывайте, ссылка действует 5 дней.<br>
		</p>
		<p>
		    Для авторизации на сайте воспользуйтесь логином и паролем:<br>
		    Логин: {{ $account->email }}<br>
		    Пароль: {{ Config::get('temp.account_password') }}
		</p>
	</div>
</body>
</html>