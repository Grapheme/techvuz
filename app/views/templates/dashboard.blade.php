<!DOCTYPE html>
<html lang="en-us">
<head>
	@include('templates.dashboard.head')
	@yield('style')
</head>
<body class="smart-style-2">
	<!--[if IE 7]><h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1><![endif]-->
	@include('templates.dashboard.header')
	@include('templates.dashboard.sidebar')
	<div id="main" role="main">
		<div id="content">
			@yield('content')
		</div>
		<!--@include('templates.dashboard.footer')-->
	</div>
	@include('templates.dashboard.scripts')
	@yield('scripts')
</body>
</html>