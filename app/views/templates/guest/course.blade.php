@extends(Helper::layout())

@section('title'){{{ $course_seo->title }}}@stop
@section('description'){{{ $course_seo->description }}}@stop
@section('keywords'){{{ $course_seo->keywords }}}@stop

@section('style')
@stop
@section('content')
{{ Helper::ta($course_seo) }}
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