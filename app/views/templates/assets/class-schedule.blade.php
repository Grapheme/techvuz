<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
    <main>
    @if(isset($module))
        <?php
            $studyPeriod = new CalcStudyPeriod();
            $studyPeriod->config(array('start_date'=>$DataNachalaObucheniya));
            ob_start();
        ?>
        <table>
            <tbody>
                <tr>
                    <td><p align="center"><strong>№ п/п</strong></p></td>
                    <td><p align="center"><strong>Наименование учебных модулей</strong></p></td>
                    <td><p align="center"><strong>Всего часов</strong></p></td>
                    <td><p align="center"><strong>Дата</strong></p></td>
                </tr>
                <tr>
                    <td><p align="center">1</p></td>
                    <td><p align="center">2</p></td>
                    <td><p align="center">3</p></td>
                    <td><p align="center">4</p></td>
                </tr>
        @foreach($module as $chapter)
                <?php $hours = 0; ?>
                <tr>
                    <td colspan="4">
                        <p align="center">{{ $chapter->title }} ({{ $chapter->hours.' '.Lang::choice('час|часа|часов',$chapter->hours) }})</p>
                    </td>
                </tr>
            @if(count($chapter->lectures))
                @foreach($chapter->lectures as $lecture)
                <tr>
                    <td><p align="center">{{ $lecture->order }}</p></td>
                    <td>{{ $lecture->title }}</td>
                    <td><p align="center">{{ $lecture->hours }}</p></td>
                    <td>{{ !empty($DataNachalaObucheniya) ? $studyPeriod->addHours($lecture->hours)->write() : '-' }}</td>
                </tr>
                @endforeach
            @endif
            @if(!empty($chapter->test))
                <tr>
                    <td colspan="2">
                        <p align="center">{{ !empty($chapter->test_title) ? $chapter->test_title : $chapter->test->title; }}</p>
                    </td>
                    <td><p align="center">{{ $chapter->test_hours }}</p></td>
                    <td>{{ !empty($DataNachalaObucheniya) ? $studyPeriod->addHours($chapter->test_hours)->write() : '-' }}</td>
                </tr>
            @endif
        @endforeach
            @if(!empty($final_test))
                <tr>
                    <td colspan="2">
                        <p align="center">{{ !empty($final_test->test_title) ? $final_test->test_title : $final_test->title; }}</p>
                    </td>
                    <td><p align="center">{{ $final_test->test_hours }}</p></td>
                    <td>{{ !empty($DataNachalaObucheniya) ? $studyPeriod->addHours($final_test->test_hours)->write() : '-' }}</td>
                </tr>
            @endif
                <tr>
                    <td colspan="2">
                        <p align="center">Итого</p>
                    </td>
                    <td><p align="center">{{ $studyPeriod->get('hours') }}</p></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <?php $RaspisanieObucheniyaPoKursu = ob_get_clean(); ?>
    @endif
        @if(isset($template) && File::exists($template))
            <?php require($template);?>
        @endif
    </main>
</body>
</html>
