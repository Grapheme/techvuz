@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_listener::where('id',Auth::user()->id)->first()->fio }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>{{ $study_test->test->title }}</h4>
            <div class="desc">
                 по курсу {{ $study_test->test->course->title }}
            </div>
        @if(Session::has('message.status') && Session::get('message.status') == 'test-result')
            <div>
                <p>{{ Session::get('message.text') }}</p>
            </div>
            @if(Session::has('message.show_repeat'))
            <a href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($study_test->test->course->title,100),'study_test_id'=>$study_test->test->id)) }}">Пройти еще раз</a>
            @endif
        @endif
            <a href="{{ URL::route('listener-study') }}">Готово</a>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop