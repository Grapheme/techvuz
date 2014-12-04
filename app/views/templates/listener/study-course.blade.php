@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <h1>{{ User_listener::where('id',Auth::user()->id)->pluck('fio') }}</h1>
    <!-- Сюда нужно вывести название организации -->
    <p class="style-light style-italic">ООО «Организация»</p>
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
                {{ Form::open(array('url'=>URL::route('listener-study-download-lectures',array('study_course_id'=>$study_course->id)), 'class'=>'clearfix','style'=>'display:block', 'method'=>'POST')) }}
                    <input type="submit" value="Скачать все лекции" class="btn btn--bordered btn--blue margin-bottom-20 pull-right">
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
                                        <input type="submit" value="Скачать" class="btn btn--bordered btn--blue margin-bottom-20 pull-right">
                                    {{ Form::close() }}
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        @if(!empty($chapter->test))
                        <tr>
                            <td colspan="3">{{ $chapter->test->title }}</td>
                            <td><a class="btn btn--bordered btn--blue" href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($module->title,100),'study_test_id'=>$chapter->test->id)) }}">Пройти</a></td>
                        </tr>
                        @endif
                    @endforeach
                    <?php
                        $studyHours = !empty($module->hours) ? round($module->hours/8)*86400 : round(Config::get('site.time_to_study_begin')/4)*86400;
                        $lostTime = myDateTime::getDiffTimeStamp(date("Y-m-d H:i:s",strtotime($study_course->start_date)+$studyHours),date("Y-m-d H:i:s",time()));
                    ?>
                    @if($study_course->start_status == 1 && $lostTime <= 0 && !empty($module->test))
                        <tr>
                            <td colspan="3">Итоговое тестирование</td>
                            <td><a class="btn btn--bordered btn--blue" href="{{ URL::route('listener-study-testing',array('study_course_id'=>$study_course->id.'-'.BaseController::stringTranslite($module->title,100),'study_test_id'=>$module->test->id)) }}">Пройти</a></td>
                        </tr>
                    @elseif($study_course->start_status == 1 && $lostTime > 0)
                        <tr>
                            <?php
                            $lostDateTime = myDateTime::getDiffDate(date("Y-m-d H:i:s",time()),date("Y-m-d H:i:s",strtotime($study_course->start_date)+$studyHours),NULL);
                            ?>
                            <td colspan="4">
                                @if($lostDateTime['d'] > 2)
                                    Итоговое тестирование будет доступно через {{ $lostDateTime['d'].' '.Lang::choice('день|дня|дней', $lostDateTime['d']) }}
                                @elseif($lostDateTime['d'] > 1)
                                    Итоговое тестирование будет доступно через {{ $lostDateTime['d'].' '.Lang::choice('день|дня|дней', $lostDateTime['d'])}} {{ $lostDateTime['h'].' '.Lang::choice('час|часа|часов', $lostDateTime['h']) }}
                                @elseif($lostDateTime['h'] > 0 && $lostDateTime['h'] < 23)
                                    Итоговое тестирование будет доступно через {{ $lostDateTime['h'].' '.Lang::choice('час|часа|часов', $lostDateTime['h']) }} {{ $lostDateTime['i'].' '.Lang::choice('минута|минуты|минут', $lostDateTime['i']) }}
                                @elseif($lostDateTime['i'] > 0)
                                    Итоговое тестирование будет доступно через {{ $lostDateTime['i'].' '.Lang::choice('минута|минуты|минут', $lostDateTime['i']) }}
                                @else
                                    Итоговое тестирование будет скоро доступно
                                @endif
                            </td>
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