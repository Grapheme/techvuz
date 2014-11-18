<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
	    <p>Добро пожаловать на образовательный портал {{ HTML::link('','ТехВУЗ.рф') }}.<br>
        Активируйте свой личный кабинет, перейдя по <a href="{{ URL::route('signup-activation',array('activate_code'=>User::find($account->id)->temporary_code)) }}">ссылке</a>.<br>
        Не откладывайте, ссылка действует 5 дней.</p>
        <p>
        Для доступа на сайт воспользуйтесь логином и паролем:
        Логин: {{ $account->email }}<br>
        Пароль: {{ Config::get('temp.account_password') }}
        </p>
	</div>
</body>
</html>