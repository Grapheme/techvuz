@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <a class="name-dashboard" href="{{ URL::route('dashboard') }}"><h1>{{ User_individual::where('id',Auth::user()->id)->pluck('fio'); }}</h1></a>
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
            @if(Session::has('message.show_result') && $study_test->test->chapter_id == 0)
            <div class="margin-top-20 margin-bottom-20">
                <a class="style-normal nowrap" href="{{ URL::route('individual-order-result-certification',array('order_id'=>$study_course->order_id,'course_id'=>$study_course->id,'format'=>'pdf')) }}">
                    <span class="icon icon-sertifikat"></span> Просмотреть результат (pdf)
                </a>
            </div>
            @endif
            <div class="margin-bottom-20">
                Требуемый результат: {{ Config::get('site.success_test_percent') }}%
            </div>
            @if(Session::has('message.show_repeat'))
            <a class="btn btn--bordered btn--blue" href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($study_test->test->course->title,100),'study_test_id'=>$study_test->test->id)) }}">Новая попытка</a>
            @endif
        @endif
        @if($study_test->test->chapter_id == 0)
            <a class="btn btn--bordered btn--blue" href="{{ URL::route('individual-study') }}">Готово</a>
        @else
            <a class="btn btn--bordered btn--blue" href="{{ URL::route('individual-study-course',array('course_translite_title'=>$study_course->id.'-'.BaseController::stringTranslite($study_test->test->course->title,100))) }}">Вернуться к лекциям</a>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop