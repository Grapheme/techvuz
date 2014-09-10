<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
			Добро пожаловать в {{ link_to('','Monety.pro') }}. 
			Активируйте свой аккаунт, перейдя по {{ link_to('activation?u='.$account->id.'&c='.$account->temporary_code,'ссылке') }}. 
			Не откладывайте, ссылка действует 72 часа.
		</p>
	</div>
</body>
</html>