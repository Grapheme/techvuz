<!doctype html>
<html class="no-js">
    <head>
	@include(Helper::acclayout('head'))
	@yield('style')
    </head>
<body>
    <div class="top-dec">
        <div class="top-dec-part part-1"></div>
        <div class="top-dec-part part-2"></div>
        <div class="top-dec-part part-3"></div>
        <div class="top-dec-part part-4"></div>
        <div class="top-dec-part part-5"></div>
        <div class="top-dec-part part-6"></div>
    </div>
    <!--[if lt IE 10]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="main-wrapper">
    @include(Helper::layout('header'))
    @include(Helper::layout('aside'))
        <main class="cabinet moderator-cabinet">
             <h2>{{ Auth::user()->name.' '.Auth::user()->surname }}</h2>
             <div class="cabinet-tabs">
                 @include(Helper::acclayout('menu'))
             </div>
            @yield('content', @$content)
        </main>
    </div>
    @yield('overlays')
    @include(Helper::acclayout('scripts'))
    @yield('scripts')
</body>
</html>