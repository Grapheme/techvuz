<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Страница не найдена :(</title>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
{{ HTML::style(Config::get('site.theme_path').'/css/main.css') }}
<style>
	html, body {
    	margin: 0;
    	padding: 0;
   		height: 100%; 
    }
  	body {
    	background: #33ace4;
    	color: #fff;
    }
    .aside-404 {
   		position: absolute;
    	top: 0;
    	left: 0;
    
    	width: 250px;
    	height: 100%;
    }
  	.container-404 {
    	padding: 0 0 0 250px;
  	}
  	.contact {
		margin: 1rem 0 0 0;
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
		position: relative;
		width: 187px;
		height: 11.6875rem;
		margin: 0 0 2.95rem;

		background: url({{ HTML::style(Config::get('site.theme_path').'/img/logo.svg') }});
		background-size: 100% 100%;
  	}
</style>

</head>
<body>
	<aside class="aside-404">

		<div class="contact">
	        <div class="phone">
	            <a href="tel:+78004400000">8 (800) 440 00 00</a>
	        </div>
	        <div class="phone-desc">
	            Звонок бесплатный
	        </div>
	    </div>

		<div class="logo"></div>

		<footer class="main-footer">
		    <div class="copy">
		        © АНО ДПО «ЦКС», 2012 - {{ date("Y") }}
		    </div>
		    <div class="dev">
		        Сделано в <a href="http://grapheme.ru">ГРАФЕМА</a>
		    </div>
		</footer>
	</aside>

	<div class="container-404">
		<h1>
			404 Ошибка
		</h1>
		<div class="desc-404">
			Запрашиваемая вами страница не найдена. Ознакомиться
			с нашими курсами вы можете в разделе «Каталог курсов»
		</div>
	</div>
</body>
</html>