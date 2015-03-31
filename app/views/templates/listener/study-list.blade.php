@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php $courses = OrderListeners::where('user_id',Auth::user()->id)->orderBy('access_status','DESC')->with('course')->with('final_test')->get();?>
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <h1>{{ $account->fio }}</h1>
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div>
            <h3>Ход обучения</h3>
            <div class="tabs usual-tabs">
                <ul>
                    <li>
                        <?php $count_courses = 0; ?>
                        @foreach($courses as $course)
                            @if($course->access_status == 1 && $course->over_status == 0)
                                <?php $count_courses++; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-11">Доступно {{ $count_courses ? '<span class="filter-count">'.$count_courses.'</span>' : '' }}</a>
                    </li>
                    <li>
                        <?php $count_courses = 0; ?>
                        @foreach($courses as $course)
                            @if($course->access_status == 1 && $course->over_status == 1)
                                <?php $count_courses++; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-13">Завершено {{ $count_courses ? '<span class="filter-count">'.$count_courses.'</span>' : '' }}</a>
                    </li>
                    <li>
                        <?php $count_courses = 0; ?>
                        @foreach($courses as $course)
                            @if($course->access_status == 1)
                                <?php $count_courses++; ?>
                            @endif
                        @endforeach
                        <a href="#tabs-14">Все {{ $count_courses ? '<span class="filter-count">'.$count_courses.'</span>' : '' }}</a>
                    </li>
                </ul>
                <div id="tabs-11">
                <?php $hasCourses = FALSE;?>
                @foreach($courses as $listener_course)
                    @if($listener_course->access_status == 1 && $listener_course->over_status == 0)
                        <?php $hasCourses = TRUE;?>
                    @endif
                @endforeach
                @if($hasCourses)
                    <table class="tech-table sortable">
                        <thead>
                            <tr>
                                <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                                <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                            </tr>
                        </thead>
                        <tbody>
                    @foreach($courses as $listener_course)
                        @if($listener_course->access_status == 1 && $listener_course->over_status == 0)
                            @include(Helper::acclayout('assets.course-tr'))
                        @endif
                    @endforeach
                        </tbody>
                    </table>
                @else
                    <p>В настоящий момент доступных курсов для обучения нет. Они появятся здесь после оплаты счета Вашей организацией.</p>
                @endif
                </div>
                <div id="tabs-13">
                    <?php $hasCourses = FALSE;?>
                    @foreach($courses as $listener_course)
                        @if($listener_course->access_status == 1 && $listener_course->over_status == 1)
                            <?php $hasCourses = TRUE;?>
                        @endif
                    @endforeach
                    @if($hasCourses)
                    <table class="tech-table sortable">
                        <tbody>
                            <tr>
                                <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                                <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                            </tr>
                            <?php $hasCourses = FALSE;?>
                    @foreach($courses as $listener_course)
                        @if($listener_course->access_status == 1 && $listener_course->over_status == 1)
                            @include(Helper::acclayout('assets.course-tr'),array('showResults'=>TRUE))
                            <?php $hasCourses = TRUE;?>
                        @endif
                    @endforeach
                        </tbody>
                    </table>
                    @else
                        <p>Вы еще не завершили обучение ни по одному из курсов.</p>
                    @endif
                </div>
                <div id="tabs-14">
                    <?php $hasCourses = FALSE;?>
                    @foreach($courses as $listener_course)
                        @if($listener_course->access_status == 1)
                            <?php $hasCourses = TRUE;?>
                        @endif
                    @endforeach
                    @if($hasCourses)
                    <table class="tech-table sortable">
                        <tbody>
                            <tr>
                                <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                                <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                            </tr>
                        @foreach($courses as $listener_course)
                            @if($listener_course->access_status == 1)
                                @include(Helper::acclayout('assets.course-tr'))
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <p>У вас нет ни одного курса. Здесь появятся все доступные и завершенные Вами курсы.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop