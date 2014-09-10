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
	{{ HTML::style('css/production.css') }}
	{{ HTML::style('css/skins.css') }}
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
	<div id="main2" role="main">
		<div id="content" class="container">
			<div class="row">

				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4" style="float:none; clear:both; margin: 0 auto">
					<div class="well no-padding">
						@include('guests.forms.sign-in')
					</div>
				</div>

                {{--
    			@if(Allow::module('users') || 1)
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
					<div class="well no-padding">
						@include('guests.forms.sign-up')
					</div>
				</div>
			    @endif
                --}}

			</div>
		</div>
	</div>

@if(Config::get('app.use_scripts_local'))
	{{HTML::script('js/vendor/jquery.min.js');}}
@else
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
@endif
	{{HTML::script('js/vendor/bootstrap.min.js');}}
	{{HTML::script('js/system/main.js');}}
	{{HTML::script('js/vendor/SmartNotification.min.js');}}
	{{HTML::script('js/vendor/jquery.validate.min.js');}}
	{{HTML::script('js/vendor/jquery.maskedinput.min.js');}}
	{{HTML::script('js/system/app.js');}}
	{{HTML::script('js/system/messages.js');}}
<script type="text/javascript">pageSetUp();</script>
{{HTML::script('js/account/guest.js');}}

@if(Allow::module('users') && 0)
{{HTML::script('js/modules/users.js');}}
<script type="text/javascript">
	loadScript("{{asset('js/vendor/jquery-form.min.js');}}", moduleFormValidtion);
</script>
@else
<script type="text/javascript">
	loadScript("{{asset('js/vendor/jquery-form.min.js');}}", runFormValidation);
</script>
@endif

</body>
</html>