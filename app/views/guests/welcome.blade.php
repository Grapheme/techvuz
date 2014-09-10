<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
 <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>{{{(isset($page_title))?$page_title:Config::get('app.default_page_title')}}}</title>
	<meta name="description" content="{{{(isset($page_description))?$page_description:''}}}">
	<meta name="keywords" content="{{{(isset($page_keywords))?$page_keywords:''}}}">
	<meta name="author" content="{{{(isset($page_author))?$page_author:''}}}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@if(Config::get('app.use_css_local'))
	{{ HTML::style('css/bootstrap.min.css') }}
@else
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
@endif
	{{ HTML::style('css/bootstrap-theme.min.css') }}
	{{ HTML::style('css/font-awesome.min.css') }}
	{{ HTML::style('css/main.css') }}
	<link rel="shortcut icon" href="{{asset('img/favicon/favicon.png')}}" type="image/x-icon">
	<link rel="icon" href="{{asset('img/favicon/favicon.png')}}" type="image/x-icon">
@if(Config::get('app.use_googlefonts'))
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
@endif
{{HTML::script('js/vendor/modernizr-2.6.2.min.js');}}
</head>
<body>
	<!--[if lt IE 7]>
		<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<main class="row content max-width-class" role="main">
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
					<h1 class="txt-color-red login-header-big">Здравствуйте.</h1>
					<div class="hero">
						<div class="pull-left login-desc-box-l">
							<h4 class="paragraph-header">Спасибо что пользуетесь нашей системой.</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
							<p>Сейчас нет страниц на сайте.<br/>Пожалуйста <a href="{{url('login')}}">авторизуйтесь</a> под администратором чтобы начать работу.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</body>
</html>