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
                $spisok[$listener->user_id]['courses'][$listener->course->id]['certificate_number'] = $listener->certificate_number;
                $spisok[$listener->user_id]['courses'][$listener->course->id]['certificate_date'] = $listener->certificate_date;
                $spisok[$listener->user_id]['courses'][$listener->course->id]['course_code'] = $listener->course->code;
                $spisok[$listener->user_id]['courses'][$listener->course->id]['course_title'] = $listener->course->title;
            endforeach;
            ?>
            <?php ob_start(); ?>
            <table>
                <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>ФИО слушателя</strong></p></td>
                    <td><p align="center"><strong>Наименование программы</strong></p></td>
                    <td><p align="center"><strong>Номер и дата удостоверения о повышении квалификации</strong></p></td>
                    <td><p align="center"><strong>Вручено</strong></p></td>
                    <td><p align="center"><strong>Подпись / дата отправки и почтовый идентификатор</strong></p></td>
                </tr>
                <?php $listener_index = 0 ;?>
                @foreach($spisok as $listener)
                    <?php $course_index = 0 ;?>
                    @foreach($listener['courses'] as $course_id => $course_info)
                        <tr>
                            <td>@if($course_index == 0)<p align="center">{{ ++$listener_index }}</p>@endif</td>
                            <td>@if($course_index == 0){{ $listener['fio'] }}@endif</td>
                            <td>{{ $course_info['course_code'] }} {{ $course_info['course_title'] }}</td>
                            <td>У-{{ str_pad($course_info['certificate_number'],4,'0',STR_PAD_LEFT) }} от {{ (new myDateTime())->setDateString($course_info['certificate_date'])->format('d.m.Y') }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php $course_index++; ?>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <?php $SpisokSluschateleyDlyaJurnala = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require($template);?>
        @endif
    </main>
</body>
</html>
