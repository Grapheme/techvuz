<!DOCTYPE html>
<html lang="en-us">
<head>
	@include('templates.admin.head')
	@yield('style')
</head>
<body class="smart-style-0">
	<!--[if IE 7]><h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1><![endif]-->
	@include('templates.admin.header')
	@include('templates.admin.sidebar')
	<div id="main" role="main">
		<div id="content">
			@yield('content')
		</div>
		<!--@include('templates.admin.footer')-->
	</div>
	@include('templates.admin.scripts')
	@yield('scripts')
	<script>
	 $.fn.select2&&$(".select2").each(function(){var a=$(this),b=a.attr("data-select-width")||"100%";a.select2({allowClear:!0,width:b}),a=null});
	</script>
</body>
</html>