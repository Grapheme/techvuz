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
                $spisok[$index]['fio'] = !empty($listener->user_listener) ? $listener->user_listener->fio : $listener->user_individual->fio;
                $spisok[$index]['course_code'] = $listener->course->code;
                $spisok[$index]['course_title'] = $listener->course->title;
            endforeach;
        ?>
        <?php ob_start();?>
        <table>
            <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>ФИО</strong></p></td>
                    <td><p align="center"><strong>Наименование программы</strong></p></td>
                </tr>
            @foreach($spisok as $index => $course)
                <tr>
                    <td><p align="center">{{ $index+1 }}</p></td>
                    <td>{{ $course['fio'] }}</td>
                    <td>{{ $course['course_code'] }} {{ $course['course_title'] }}</td>
                </tr>
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
