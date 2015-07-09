<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>

    <h3>{{ Config::get('mail.forms.quick_record.subject') }}</h3>

    <p>
        @if (isset($username) && $username)
            Имя: {{ $username }}<br />
        @endif
        @if (isset($phone) && $phone)
            Телефон: {{ $phone }}<br />
        @endif
        @if (isset($email) && $email)
            e-mail: {{ $email }}<br />
        @endif
    </p>
</div>
</body>
</html>