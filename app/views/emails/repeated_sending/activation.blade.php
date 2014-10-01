<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
			Вы запросили повторную активацию аккаунта.<br>
			Для активации аккаунта, перейдя по <a href="{{ URL::route('signup-activation',array('activate_code'=>User::find($account->id)->temporary_code)) }}">ссылке</a>.<br>
		</p>
	</div>
</body>
</html>