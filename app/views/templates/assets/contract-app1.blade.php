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
        <?php $ObchiyObemDPP = 0; ?>
        <?php ob_start();?>
        <table border="1" cellpadding="0" cellspacing="0">
            <tbody>
                <tr>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="font-size: 16px;" align="center"><strong>№ п/п</strong></p></td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="font-size: 16px;" align="center"><strong>ФИО слушателя</strong></p></td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="font-size: 16px;" align="center"><strong>Должность </strong></p></td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="font-size: 16px;" align="center"><strong>Место жительства</strong></p></td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="font-size: 16px;" align="center"><strong>Телефон, </strong></p>
                        <p style="font-size: 16px;" align="center"><strong>E-mail</strong></p>
                    </td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="font-size: 16px;" align="center"><strong>Образование</strong></p>
                        <p style="font-size: 16px;" align="center">
                            Номер и дата выдачи документа о высшем / среднем профессиональном, наименование специальности,
                            учебного заведения
                        </p>
                    </td>
                    <td valign="top" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="font-size: 16px;" align="center"><strong>Наименование ДПП</strong></p></td>
                </tr>
                <?php $index = 1;?>
            @foreach($spisok as $listener_id => $listener)
                <tr>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="text-align: center; font-size: 16px;">{{ $index }}</p></td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['fio'] : $listener['individual']['fio'] }}</p></td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['position'] : $listener['individual']['position'] }}</p></td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;"><p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['postaddress'] : $listener['individual']['postaddress'] }}</p></td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['phone'] : $listener['individual']['phone'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['email'] : $listener['individual']['email'] }}</p>
                    </td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['education'] : $listener['individual']['education'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['education_document_data'] : $listener['individual']['document_education'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['specialty'] : $listener['individual']['specialty'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ !empty($listener['listener']) ? $listener['listener']['educational_institution'] : $listener['individual']['educational_institution'] }}</p>
                    </td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                    @if(count($listener['course']))
                        <p style="text-align: center; font-size: 16px;">{{ $listener['course'][0]['code'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ $listener['course'][0]['title'] }}</p>
                        <?php $ObchiyObemDPP += (int)$listener['course'][0]['hours']; ?>
                        <p style="text-align: center; font-size: 16px;">Общий объём ДПП - {{ @$ObchiyObemDPP }} часов</p>
                        <!-- <p style="text-align: center; font-size: 16px;">Срок освоения ДПП - {{ @$SrokOsvoeniyaPDD }} дней</p> -->
                    @endif
                    </td>
                </tr>
        @if(count($listener['course']) > 1)
            @foreach($listener['course'] as $course_index => $course)
                @if($course_index > 0)
                <tr>
                    <td colspan="6"></td>
                    <td style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="text-align: center; font-size: 16px;">{{ $course['code'] }}</p>
                        <p style="text-align: center; font-size: 16px;">{{ $course['title'] }}</p>
                    </td>
                </tr>
                <?php $ObchiyObemDPP += (int)$course['hours']; ?>
                @endif
            @endforeach
        @endif
                <tr>
                    <td colspan="7" style="border-width: 1px;border-color: #000;border-style: solid; padding: 5px;">
                        <p style="text-align: center; font-size: 16px;"><strong>Я, {{ !empty($listener['listener']) ? $listener['listener']['fio'] : $listener['individual']['fio'] }}, подтверждаю достоверность вышеуказанной информации,
                        с договором ознакомлен(а) <span style="margin-left: 200px;">(подпись, дата)_____________________</span></strong></p>
                    </td>
                </tr>
                <?php $index++; ?>
            @endforeach
            </tbody>
        </table>
        <?php $TablicaSluschateleyDlyaDogovora = ob_get_clean(); ?>
        <?php $SrokOsvoeniyaPDD = round($ObchiyObemDPP/8); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require_once($template);?>
        @endif
    </main>
</body>
</html>
