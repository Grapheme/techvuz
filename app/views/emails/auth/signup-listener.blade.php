<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
			Добро пожаловать в {{ HTML::link('','ТехВУЗ.рф') }}
		</p>
		<p>
		    Для авторизации на сайте воспользуйтесь логином и паролем:<br>
		    Логин: {{ $account->email }}<br>
		    Пароль: {{ Config::get('temp.account_password') }}
		</p>
	</div>
</body>
</html>