@extends(Helper::layout())

@section('title'){{{ $course_seo->title }}}@stop
@section('description'){{{ $course_seo->description }}}@stop
@section('keywords'){{{ $course_seo->keywords }}}@stop

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
        <a class="btn-request" href="{{ pageurl('registration') }}">Оформить заявку</a>
    @elseif(isOrganizationORIndividual())
        <div class="new-order-holder">
            <a href="{{ URL::route('ordering-select-courses') }}#{{ $course->id }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
        </div>
    @endif
    @if(!empty($course->trial_test))
        <a class="btn-request" href="{{ URL::route('course-page-trial-test', $course->seo->url) }}">{{ $course->trial_test->title }}</a>
    @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop