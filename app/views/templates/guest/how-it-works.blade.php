@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="how-it-works">
    <h2>{{ $page->block('top_h2') }}</h2>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="htw">
    {{ $page->block('content') }}
    </section>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop