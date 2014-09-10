	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>{{{(isset($page_title))?$page_title:'Панель администрирования'}}}</title>
	<meta name="description" content="{{{(isset($page_description))?$page_description:''}}}">
	<meta name="keywords" content="{{{(isset($page_keywords))?$page_keywords:''}}}">
	<meta name="author" content="{{{(isset($page_author))?$page_author:''}}}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
@if(Config::get('app.use_css_local'))
	{{ HTML::style('css/bootstrap.min.css') }}
@else
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
@endif
	{{ HTML::style('css/font-awesome.min.css') }}
	{{ HTML::style('css/production_unminified.css') }}
	{{ HTML::style('css/skins.css') }}
	<link rel="shortcut icon" href="{{asset('img/favicon/favicon.png')}}" type="image/x-icon">
	<link rel="icon" href="{{asset('img/favicon/favicon.png')}}" type="image/x-icon">
@if(Config::get('app.use_googlefonts'))
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic&subset=latin,cyrillic-ext,cyrillic,latin-ext' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>
@endif

    <script>
    var base_url = '<?=URL::to('')?>';
    </script>
