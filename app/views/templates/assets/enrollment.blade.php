<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
    <main>
        @if(isset($SpisokSluschateley))
        <?php
            $spisok = array();
            foreach($SpisokSluschateley->listeners as $index => $listener):
                $spisok[$listener->user_id]['fio'] = !empty($listener->user_listener) ? $listener->user_listener->fio : $listener->user_individual->fio ;
                $spisok[$listener->user_id]['courses'][$listener->course->id]['course_code'] = $listener->course->code;
                $spisok[$listener->user_id]['courses'][$listener->course->id]['course_title'] = $listener->course->title;
            endforeach;
        ?>
        <?php ob_start();?>
        <table class="default-table" style="border-collapse: collapse;">
            <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>ФИО</strong></p></td>
                    <td><p align="center"><strong>Наименование программы</strong></p></td>
                </tr>
                <?php $listener_index = 0 ;?>
            @foreach($spisok as $listener)
                <?php $course_index = 0 ;?>
                @foreach($listener['courses'] as $course_id => $course_info)
                <tr>
                    <td>@if($course_index == 0){{ ++$listener_index }}@endif</td>
                    <td>@if($course_index == 0){{ $listener['fio'] }}@endif</td>
                    <td>{{ $course_info['course_code'] }} {{ $course_info['course_title'] }}</td>
                </tr>
                    <?php $course_index++; ?>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <?php $SpisokSluschateleyDlyaPrikaza = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>
