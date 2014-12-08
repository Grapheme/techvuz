@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <h1>{{ $account->fio }}</h1>
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>{{ $study_test->test->title }}</h4>
            <div class="desc">
                 по курсу {{ $study_test->test->course->title }}
            </div>
        @if(Session::has('message.status') && Session::get('message.status') == 'test-result')
            <div>
                {{ Session::get('message.text') }}
            </div>
            @if(Session::has('message.show_repeat'))
            <a href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($study_test->test->course->title,100),'study_test_id'=>$study_test->test->id)) }}">Пройти еще раз</a>
            @endif
        @endif
        @if($study_test->test->chapter_id == 0)
            <a href="{{ URL::route('listener-study') }}">Готово</a>
        @else
            <a href="{{ URL::route('listener-study-course',array('course_translite_title'=>$study_course->id.'-'.BaseController::stringTranslite($study_test->test->course->title,100))) }}">Готово</a>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop