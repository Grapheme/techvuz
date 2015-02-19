@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="how-it-works">
    {{ $page->block('top_h2') }}
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="htw">
    {{ $page->block('content') }}
    </section>
    <div class="seo">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop