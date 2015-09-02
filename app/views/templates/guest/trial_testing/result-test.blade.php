@section('title'){{ $course_seo->title }}@stop
@section('description'){{ $course_seo->description }}@stop
@section('keywords'){{ $course_seo->keywords }}@stop

@extends(Helper::layout())
@section('style')
@stop
@section('content')
    <main class="contacts">
        <h1>{{ $test->title }}</h1>

        <div class="desc">
            по курсу "{{ $course->title }}"
        </div>
        <div>
            @if(Session::has('message.status') && Session::get('message.status') == 'test-result')
                <div class="margin-top-20 margin-bottom-20">
                    {{ Session::get('message.text') }}
                </div>
                <div class="margin-bottom-20">
                    Требуемый результат: {{ Config::get('site.success_test_percent') }}%
                </div>
                @if(Session::has('message.show_repeat'))
                    <a class="btn btn--bordered btn--blue"
                       href="{{ URL::route('course-page-trial-test', $course_seo->url) }}">Новая попытка</a>
                @else
                    @if(Auth::guest())
                        <a class="btn-request" href="{{ pageurl('registration') }}">Оформить заявку</a>
                    @elseif(isOrganizationORIndividual())
                        <div class="new-order-holder">
                            <a href="{{ URL::route('ordering-select-courses') }}#{{ $course->id }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </main>
@stop
@section('overlays')
@stop
@section('scripts')
    @if(Session::has('message.status') === FALSE)
        <script type="application/javascript">
            window.location.href = '{{ URL::route('course-page', $course_seo->url) }}';
        </script>
    @endif
@stop