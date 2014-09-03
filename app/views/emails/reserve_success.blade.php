<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
</head>
<body>
	<div>
		<p>
            Осуществлено бронирование номера &laquo;{{ $room->name }}&raquo;<br/>
            с {{ $date_start }} по {{ $date_stop }}<br/>
            Имя гостя: {{ $name }}<br/>
            Контактные данные: {{ $contact }}<br/>
		</p>
	</div>
</body>
</html>