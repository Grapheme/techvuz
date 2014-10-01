<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Сброс пароля</h2>
		<div>
			Чтобы сбросить пароль, заполните эту <a href="{{ URL::route('password-reset.show', array($token)) }}">форму</a>.<br/>
			Эта ссылка истекает через {{ round(Config::get('auth.reminder.expire', 60)/1440) }} дней.
		</div>
	</body>
</html>