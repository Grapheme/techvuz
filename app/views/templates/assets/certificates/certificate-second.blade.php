<!doctype html>
<html class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
<main>
    <div class="sert-doc-wrapper">
        @if(isset($template) && File::exists($template))
            <?php require($template);?>
        @endif
    </div>
</main>
</body>
</html>
