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
<main class="catalog-study-plan">
    <p class="study-plan-desc">Учебный план<br>дополнительной профессиональной программы</p>
    <h1>{{ $course->seo->h1 }}</h1>
    <div class="desc">
    {{ $course->description }}
    </div>
    {{ $course->curriculum }}
    <div class="btn-request-holder">
    @if(Auth::guest())
        <a class="btn-request" href="{{ URL::route('page', 'registration') }}">Оформить заявку</a>
    @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop