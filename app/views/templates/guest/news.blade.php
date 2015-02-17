@section('title')Новости@stop
@section('description')Полный список новостий@stop
@section('keywords')@stop
@extends(Helper::layout())
@section('style') @stop
@section('content')
    <main>
        <h2>Новости</h2>
        <div class="margin-top-40">
        @foreach($news as $single_news)
            <div class="row margin-bottom-20">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 style-light">
                    {{ Helper::rdate("j M Y", strtotime($single_news->published_at)) }}
                </div>
                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 style-light no-padding padding-right-20">
                    <div class="news-title">
                        {{{ $single_news->meta->title }}}
                    </div>
                    <div class="news-text">
                        {{{ $single_news->meta->content }}}
                    </div>
                </div>
            </div>
        @endforeach
            <div class="text-center">
                {{ $news->links(); }}
            </div>
        </div>
    </main>
@stop
@section('overlays')
@stop
@section('scripts')
    @if(Auth::guest() && Input::has('login'))
        <script>
            $(function(){
                $(".js-login").click();
            });
        </script>
    @endif
@stop