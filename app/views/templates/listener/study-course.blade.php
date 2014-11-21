@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h2>{{ User_listener::where('id',Auth::user()->id)->pluck('fio') }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h4>{{ $module->code }}. {{ $module->title }}</h4>
            <div class="desc">
                {{ $module->description }}
            </div>
            @if($module->metodicals->count())
            <div>
                <h4>Специализированная документация</h4>
                <ul>
                @foreach($module->metodicals as $metodical)
                    @if(!empty($metodical->document->path) && File::exists(public_path($metodical->document->path)))
                    <li>
                        <a href="{{ asset($metodical->document->path) }}" target="_blank"> {{ $metodical->title }}</a>.<br>
                        {{ $metodical->description }}
                    </li>
                    @endif
                @endforeach
                </ul>
            </div>
            @endif
        @if($module->chapters->count())
            <div>
                {{ Form::open(array('url'=>URL::route('listener-study-download-lectures',array('study_course_id'=>$study_course->id)), 'style'=>'display:inline-block', 'method'=>'POST')) }}
                    {{ Form::submit('Скачать все лекции') }}
                {{ Form::close() }}
                <table class="tech-table sortable">
                    <tbody>
                        <tr>
                            <th>№</th>
                            <th>Название</th>
                            <th>Часов</th>
                            <th>Форма контроля</th>
                        </tr>
                    @foreach($module->chapters as $chapter)
                        <tr>
                            <td>{{ $chapter->order }}</td>
                            <td>{{ $chapter->title }}</td>
                            <td>{{ $chapter->hours }}</td>
                            <td>Тестирование</td>
                        </tr>
                        @if($chapter->lectures->count())
                            @foreach($chapter->lectures as $lecture)
                            <tr>
                                <td>{{ $chapter->order }}.{{ $lecture->order }}</td>
                                <td>{{ $lecture->title }}</td>
                                <td>{{ $lecture->hours }}</td>
                                <td>
                                    {{ Form::open(array('url'=>URL::route('listener-study-download-lecture',array('study_course_id'=>$study_course->id,'lecture_id'=>$lecture->id)), 'style'=>'display:inline-block', 'method'=>'POST')) }}
                                        {{ Form::submit('Скачать') }}
                                    {{ Form::close() }}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        @if(!empty($chapter->test))
                        <tr>
                            <td colspan="3">{{ $chapter->test->title }}</td>
                            <td><a href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($module->title,100),'study_test_id'=>$chapter->test->id)) }}">Пройти</a></td>
                        </tr>
                        @endif
                    @endforeach
                    <?php
                        $lostTime = myDateTime::getDiffTimeStamp(date("Y-m-d H:i:s",strtotime($study_course->start_date)+Config::get('site.time_to_study_begin')),date("Y-m-d H:i:s",time()));
                    ?>
                    @if($study_course->start_status == 1 && $lostTime <= 0 && !empty($module->test))
                        <tr>
                            <td colspan="3">Итоговое тестирование</td>
                            <td><a href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($module->title,100),'study_test_id'=>$module->test->id)) }}">Пройти</a></td>
                        </tr>
                    @elseif($study_course->start_status == 1 && $lostTime > 0)
                        <tr>
                            <?php
                            $lostDateTime = myDateTime::getDiffDate(date("Y-m-d H:i:s",time()),date("Y-m-d H:i:s",strtotime($study_course->start_date)+Config::get('site.time_to_study_begin')),NULL);
                            ?>
                            <td colspan="4">Итоговое тестирование будет доступно через {{ ($lostDateTime['d']*24)+$lostDateTime['h'].' '.Lang::choice('час|часа|часов', ($lostDateTime['d']*24)+$lostDateTime['h']) }}</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop