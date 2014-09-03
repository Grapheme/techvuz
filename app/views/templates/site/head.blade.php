@section('title')
{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}
@stop
@section('description')
{{{ isset($page_description) ? $page_description : Config::get('app.default_page_description') }}}
@stop
@section('keywords')
{{{ isset($page_keywords) ? $page_keywords : Config::get('app.default_page_keywords') }}}
@stop
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title')</title>
        <meta name="description" content="@yield('description')">
        <meta name="keywords" content="@yield('keywords')">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        {{ HTML::stylemod('css/main.css') }}
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
        {{ HTML::scriptmod('js/vendor/modernizr-2.6.2.min.js') }}
