@if(@is_object($page->seo))
    @section('title'){{ $page->seo->title ? $page->seo->title : $page->name }}@stop
    @section('description'){{ $page->seo->description }}@stop
    @section('keywords'){{ $page->seo->keywords }}@stop
@elseif (@is_object($page->meta->seo))
    @section('title'){{ $page->meta->seo->title ? $page->meta->seo->title : $page->name }}@stop
    @section('description'){{ $page->meta->seo->description }}@stop
    @section('keywords'){{ $page->meta->seo->keywords }}@stop
@elseif (@is_object($page->meta))
    @section('title'){{{ $page->name }}}@stop
@elseif (@is_object($seo))
    @section('title'){{ $seo->title }}@stop
    @section('description'){{ $seo->description }}@stop
    @section('keywords'){{ $seo->keywords }}@stop
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
        <div class="overlay">
            @yield('overlays')
            @if(Auth::guest())
                @include(Helper::layout('forms.login'))
                @include(Helper::layout('forms.restore-password'))
            @endif
        </div>
    </div>
    @include(Helper::layout('scripts'))
    @yield('scripts')
</body>
</html>