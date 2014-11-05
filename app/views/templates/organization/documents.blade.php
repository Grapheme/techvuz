<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
    <main>
        @if(isset($order))
            <?php extract($order, EXTR_PREFIX_ALL, "order");?>
        @endif
        @if(isset($account))
            <?php extract($account, EXTR_PREFIX_ALL, "company");?>
        @endif
        @if(isset($listener))
            <?php extract($listener, EXTR_PREFIX_ALL, "listener");?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>