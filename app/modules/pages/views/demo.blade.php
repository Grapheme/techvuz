@extends(Helper::layout())


@if (@is_object($page->meta->seo))
    @section('title'){{ $page->meta->seo->title }}@stop
    @section('description'){{ $page->meta->seo->description }}@stop
    @section('keywords'){{ $page->meta->seo->keywords }}@stop
@else
    @section('title')
{{{ $page->meta->title }}}@stop
    @section('description')
{{{ striptags($page->meta->preview) }}}@stop
@endif

@section('style')
    {{ HTML::style('css/fotorama.css') }}
@stop


@section('content')
<main1>
    <h1>
        {{ @is_object($page->meta->seo) && $page->meta->seo->h1 ? $page->meta->seo->h1 : $page->title }}
    </h1>

    <p class="news-date">
        {{ date("d/m/Y", strtotime($page->created_at)) }}
    </p>


    @if (@is_object($page->blocks) && $page->blocks->count())
        <h3>Блоки страницы</h3>
        <hr />
        <div>
            {{ Helper::ta_($page->blocks) }}
            @foreach($page->blocks as $block)
            &laquo;<strong>{{ $block->name }}</strong>&raquo; [ <i>{{ $block->slug }}</i> ]<br/>
            @if (is_object($block->meta))
            {{ $block->meta->content }}
            @endif
            @endforeach
        </div>
        <hr />
    @endif

</main1>
@stop


@section('scripts')
    {{ HTML::script('js/vendor/fotorama.js') }}
@stop