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
{{ HTML::style(Config::get('site.theme_path').'/css/vendor.css') }}
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}
{{ HTML::style('css/font-awesome.min.css') }}
{{ HTML::script(Config::get('site.theme_path').'/js/vendor/modernizr.js') }}