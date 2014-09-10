@extends(Helper::layout())


@if (@is_object($news->meta->seo))
    @section('title'){{ $news->meta->seo->title }}@stop
    @section('description'){{ $news->meta->seo->description }}@stop
    @section('keywords'){{ $news->meta->seo->keywords }}@stop
@else
    @section('title')
{{{ $news->meta->title }}}@stop
    @section('description')
{{{ striptags($news->meta->preview) }}}@stop
@endif

@section('style')
    {{ HTML::style('css/fotorama.css') }}
@stop


@section('content')
<main1>
    <h1>
        {{ @is_object($news->meta->seo) && $news->meta->seo->h1 ? $news->meta->seo->h1 : $news->meta->title }}
    </h1>

    <p class="news-date">
        {{ date("d/m/Y", strtotime($news->published_at)) }}
    </p>

    <div class="news-desc">
        <h3>Анонс новости</h3>
        {{ $news->meta->preview }}
    </div>

    <div class="news-desc">
        <h3>Содержание новости</h3>
        {{ $news->meta->content }}
    </div>

    <hr />

    @if (@is_object($news->meta->photo))
        <h3>Изображение</h3>
        <img src="{{ URL::to($news->meta->photo->thumb()) }}">
    @endif

    @if (@is_object($news->meta->gallery) && $news->meta->gallery->photos->count())
        <h3>Галерея (слайдер)</h3>
        <div class="fotorama" data-nav="false" data-width="100%" data-fit="contain" style="width:300px">
        @foreach($news->meta->gallery->photos as $photo)
        <img src="{{ URL::to($photo->full()) }}">
        @endforeach
        </div>
    @endif

</main1>
@stop


@section('scripts')
    {{ HTML::script('js/vendor/fotorama.js') }}
@stop