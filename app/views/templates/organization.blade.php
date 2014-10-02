@if (isset($page) && !empty($page) && is_array($page))
    @section('title'){{ $page['title'] ? $page['title'] : 'Кабинет компании' }}@stop
    @section('description'){{ $page['description'] ? $page['description'] : '' }}@stop
    @section('keywords'){{ $page['keywords'] ? $page['keywords'] : '' }}@stop
@endif
<!doctype html>
<html class="no-js">
    <head>
	@include(Helper::layout('head'))
	@yield('style')
    </head>
<body>
    <!--[if lt IE 10]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="main-wrapper">
    @include(Helper::layout('header'))
    @include(Helper::layout('aside'))
    @yield('content', @$content)
    </div>
    @include(Helper::layout('scripts'))
    @yield('overlays')
    @yield('scripts')
</body>
</html>