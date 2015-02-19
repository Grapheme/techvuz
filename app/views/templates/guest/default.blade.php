<?
/**
 * TITLE: Стандартная страница
 */
?>
@extends(Helper::layout())
@section('style')@stop
@section('content')
<main class="contacts">
    @if(!empty($page->seo->h1)) <h1>{{ $page->seo->h1 }}</h1> @endif
    @if (count($page->blocks))
        @foreach ($page->blocks as $block)
            {{ $page->block($block->slug) }}
        @endforeach
    @endif
</main>
@stop
@section('scripts')
@stop