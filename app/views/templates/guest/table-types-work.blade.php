<?
/**
 * TITLE: Таблица видов работ
 */
?>
@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="catalog">
    @if(!empty($page->seo->h1))<h1>{{ $page->seo->h1 }}</h1>@endif
    <div class="desc">
        {{ $page->block('seo') }}
    </div>
    <div class="desc margin-top-20">
        @if (count($page->blocks))
            @foreach ($page->blocks as $block)
                @if($block->slug != 'seo')
                    {{ $page->block($block->slug) }}
                @endif
            @endforeach
        @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop