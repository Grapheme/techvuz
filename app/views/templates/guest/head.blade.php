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
<meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<!-- <link rel="icon" type="image/png" href="{{ Config::get('site.theme_path') }}/favicon.png"> -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ URL::to('apple-touch-ico') }}n-144x144.png" />
<link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ URL::to('apple-touch-ico') }}n-152x152.png" />
<link rel="icon" type="image/png" href="{{ URL::to('favicon-32x32.png') }}" sizes="32x32" />
<link rel="icon" type="image/png" href="{{ URL::to('favicon-16x16.png') }}" sizes="16x16" />
<meta name="application-name" content="ТЕХВУЗ.РФ"/>
<meta name="msapplication-TileColor" content="#FFFFFF" />
<meta name="msapplication-TileImage" content="{{ URL::to('mstile-144x144.png') }}" />
@if(File::exists(public_path('css/vendor.css')))
{{ HTML::style(Config::get('site.theme_path').'/css/vendor.css') }}
@endif
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}
{{ HTML::style('css/fotorama.css') }}
{{ HTML::style('css/font-awesome.min.css') }}
{{ HTML::script(Config::get('site.theme_path').'/js/vendor/modernizr-2.6.2.min.js') }}