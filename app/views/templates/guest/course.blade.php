@extends(Helper::layout())

@if (@is_object($course->meta->seo))
    @section('title'){{ $course->seo->title }}@stop
    @section('description'){{ $course->seo->description }}@stop
    @section('keywords'){{ $course->seo->keywords }}@stop
@else
    @section('title'){{{ $course->title }}}@stop
    @section('description')@stop
@endif

@section('style')
@stop
@section('content')
<main class="catalog">
    <h2>{{ $course->seo->h1 }}</h2>
    <div class="desc">
    {{ $course->description }}
    </div>
    {{ $course->curriculum }}
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop