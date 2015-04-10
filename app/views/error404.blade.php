<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Страница не найдена :(</title>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}
<style>
	.main-wrapper {
		height: 100%;
	}
	.main-footer {
		position: absolute;
		left: 0;
		bottom: 2em;

		font-size: 0.8125em;
    	font-weight: normal;
	}
	.main-footer .copy {
		margin: 0 0 1.05rem;
    	text-transform: uppercase;
	}
	.main-footer .dev a {
		border-bottom: 1px solid rgba(255, 255, 255, 0.5);
    	border-color: rgba(255, 255, 255, 0.5);
    	text-decoration: none;
    	transition: border-color 0.4s ease 0s;
	}
	.main-footer .dev a:hover {
		border-color: transparent;
	}
	html, body {
    	margin: 0;
    	padding: 0;
   		height: 100%;
    }
  	body {
    	background: #33ace4;
    	color: #fff;
    }
    .logo a {
    	position: absolute;
    	top: 0;
    	left: 0;
    	width: 100%;
    	height: 100%;
    }
    .aside-404 {
   		position: absolute;
    	top: 0;
    	left: 0;

    	width: 250px;
    	height: 100%;
    }
  	.container-404 {
  		display: table;
  		height: 100%;
    	padding: 0 0 0 300px;
  	}
  	.container-404-cell {
  		display: table-cell;
  		vertical-align: middle;
  	}
  	.contact {
		margin: 1rem 0 1rem 0;
  	}
  	.phone {
		font-size: 1.2em;
		line-height: 1.9em;
		font-weight: 400;
  	}
  	.phone-desc {
		font-size: 12em / $hfs;
		line-height: (14.4em / 12);
  	}
  	.logo {
		#position: relative;
        margin-top: 100px;
        width: 187px;
		height: 11.6875rem;

		background: url( {{ Config::get('site.theme_path').'/img/logo.svg' }} );
		background-size: 100% 100%;
  	}
  	.container-404-cell h1 {
  		margin-bottom: 1.5rem;
  	}
  	.desc-404 {
  		font-weight: 300;
  	}
</style>

</head>
<body>
	<div class="main-wrapper">
        <header class="main-header  clearfix">
            <div class="top-dec">
                <div class="top-dec-part part-1"></div>
                <div class="top-dec-part part-2"></div>
                <div class="top-dec-part part-3"></div>
                <div class="top-dec-part part-4"></div>
                <div class="top-dec-part part-5"></div>
                <div class="top-dec-part part-6"></div>
            </div>
            <aside class="aside-404">
                <div class="contact">
                    <div class="phone phone-stack">
                        <a href="tel:+74997058688" data-type="moscow" data-name="Москва" class="js-phone">8 (499) 705-86-88</a>
                        <a href="tel:+78632990714" data-type="rostov" data-name="Ростов-на-Дону" class="js-phone">8 (863) 299-07-14</a>
                        <a href="tel:+78003338654" data-type="other" class="js-phone active">8 (800) 333-86-54</a>
                    </div>
                    <div class="phone-desc phone-links">
                        <a href="#" data-type="moscow" data-name="Москва" class="js-phone-link">Москва</a>
                        <a href="#" data-type="rostov" data-name="Ростов-на-Дону" class="js-phone-link">Ростов-на-Дону</a>
                        <a href="#" data-type="other" class="js-phone-link active">Другие регионы</a>
                    </div>
                </div>
                <div class="logo"><a class="moder-logo-link" href="{{ URL::route('mainpage') }}"></a></div>
                @include(Helper::layout('footer'))
            </aside>
        </header>
		<div class="container-404">
			<div class="container-404-cell">
				<h1>404 Ошибка</h1>
				<div class="desc-404">
					Запрашиваемая вами страница не найдена. Ознакомиться<br>
					с нашими курсами вы можете в разделе <a href="{{ pageurl('catalog') }}">«Каталог курсов»</a>
				</div>
			</div>
		</div>
	</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/vendor/jquery.min.js');}}"><\/script>')</script>
    {{ HTML::script(Config::get('site.theme_path').'/js/index.js') }}
</body>
</html>