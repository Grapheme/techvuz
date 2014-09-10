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
{{ HTML::style('theme/css/normalize.css') }}
{{ HTML::style('theme/css/main.css') }}
{{ HTML::style('css/font-awesome.min.css') }}
@if(Config::get('app.use_googlefonts'))
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,800,400italic&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
@endif