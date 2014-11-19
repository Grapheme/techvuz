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
            foreach($SpisokSluschateley->listeners as $listener):
                $spisok[$listener->user_id]['listener'] = !empty($listener->user_listener) ? $listener->user_listener->toArray() : array();
                $spisok[$listener->user_id]['individual'] = !empty($listener->user_individual) ? $listener->user_individual->toArray() : array();
                $spisok[$listener->user_id]['course'][] = !empty($listener->course) ? $listener->course->toArray() : array();
            endforeach;
        ?>
        <?php ob_start();?>
        <table>
            <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>ФИО слушателя</strong></p></td>
                    <td><p align="center"><strong>Должность </strong></p></td>
                    <td><p align="center"><strong>Место жительства</strong></p></td>
                    <td>
                        <p align="center"><strong>Телефон, </strong></p>
                        <p align="center"><strong>E-mail</strong></p>
                    </td>
                    <td>
                        <p align="center"><strong>Образование</strong></p>
                        <p align="center">
                            Номер и дата выдачи документа о высшем / среднем профессиональном, наименование специальности,
                            учебного заведения
                        </p>
                    </td>
                    <td><p align="center"><strong>Наименование ДПП</strong></p></td>
                </tr>
                <?php $index = 1;?>
            @foreach($spisok as $listener_id => $listener)
                <tr>
                    <td><p align="center">{{ $index }}</p></td>
                    <td><p align="center">{{ $listener['listener']['fio'] }}</p></td>
                    <td><p align="center">{{ $listener['listener']['position'] }}</p></td>
                    <td><p align="center">{{ $listener['listener']['postaddress'] }}</p></td>
                    <td>
                        <p align="center">{{ $listener['listener']['phone'] }}</p>
                        <p align="center">{{ $listener['listener']['email'] }}</p>
                    </td>
                    <td>
                        <p align="center">{{ $listener['listener']['education'] }}</p>
                        <p align="center">{{ $listener['listener']['education_document_data'] }}</p>
                        <p align="center">{{ $listener['listener']['specialty'] }}</p>
                        <p align="center">{{ $listener['listener']['educational_institution'] }}</p>
                    </td>
                    <td>
                    @if(count($listener['course']))
                        <p align="center">{{ $listener['course'][0]['code'] }}</p>
                        <p align="center">{{ $listener['course'][0]['title'] }}</p>
                    @endif
                    </td>
                </tr>
        @if(count($listener['course']) > 1)
            @foreach($listener['course'] as $course_index => $course)
                @if($course_index > 0)
                <tr>
                    <td colspan="6"></td>
                    <td>
                        <p align="center">{{ $course['code'] }}</p>
                        <p align="center">{{ $course['title'] }}</p>
                    </td>
                </tr>
                @endif
            @endforeach
        @endif
                <tr>
                    <td colspan="7">
                        <p align="center">Я, {{ $listener['listener']['fio'] }}, подтверждаю достоверность вышеуказанной информации,
                        с договором ознакомлен ( подпись, дата)</p>
                    </td>
                </tr>
                <?php $index++; ?>
            @endforeach
            </tbody>
        </table>
        <?php $TablicaSluschateleyDlyaDogovora = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>
