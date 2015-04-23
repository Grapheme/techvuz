@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="how-it-works">
    @if(!empty($page->seo->h1))<h1>{{ $page->seo->h1 }}</h1>@endif
    <section class="htw">
    {{ $page->block('content') }}
    </section>
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop