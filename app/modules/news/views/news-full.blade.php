@extends(Helper::layout())


@section('style')
	<link rel="stylesheet" href="/css/fotorama.css">
@stop


@section('footer-class') white-footer @stop


@section('content')

    <section class="normal-page">
        <div class="wrapper">
            <h1>Новости</h1>
            <ul class="news-list">
                <li>
                    <h3>
                        <a href="#">{{ $news->meta->title }}</a>
                    </h3>
                    <div class="news-date">{{ Helper::rdate("d M Y", strtotime($news->published_at)) }}</div>
                    <div class="news-text">
                        <p>
                            {{ $news->meta->preview }}
                        </p>  
                        <p>
                            {{ $news->meta->content }}
                        </p>
                    </div>
            </ul>
            <ul class="news-year">
                <li class="active"><a href="#">2014</a>
                <li><a href="#">2013</a>
                <li><a href="#">2012</a>
                <li><a href="#">2011</a>
            </ul>
        </div>
    </section>

@stop


@section('scripts')
    <script src="/js/vendor/fotorama.js"></script>
@stop