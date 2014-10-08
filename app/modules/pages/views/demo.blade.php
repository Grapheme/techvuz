@extends(Helper::layout())


@if (@is_object($this->seo))
    @section('title'){{ $this->seo->title }}@stop
    @section('description'){{ $this->seo->description }}@stop
    @section('keywords'){{ $this->seo->keywords }}@stop
@else
    @section('title')
{{{ $page->meta->title }}}@stop
    @section('description')@stop
@endif

@section('style')
    {{ HTML::style('css/fotorama.css') }}
@stop


@section('content')
<main1>
    <h1>
        {{ @is_object($this->seo) && $this->seo->h1 ? $this->seo->h1 : $page->title }}
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
                {{ $block->content }}
            @endforeach
        </div>
        <hr />
    @endif

</main1>
@stop


@section('scripts')
    {{ HTML::script('js/vendor/fotorama.js') }}
@stop