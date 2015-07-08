<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<div>

    <h3>{{ Config::get('mail.forms.course_request.subject') }}</h3>

    <p>
        @if (isset($name) && $name)
            Имя: {{ $name }}<br />
        @endif
        @if (isset($email) && $email)
            e-mail: {{ $email }}<br />
        @endif
        @if (isset($course) && $course)
            Ищет курс: {{ $course }}<br />
        @endif
    </p>
</div>
</body>
</html>