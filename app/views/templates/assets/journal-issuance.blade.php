<!doctype html>
<html class="no-js">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}</title>
</head>
<body>
    <main>
        @if(isset($DannueResultatovAttestacii))
            <?php
                ob_start();
            ?>
            <table>
                <tbody>
            @foreach($DannueResultatovAttestacii as $question_id => $question)
                <?php $KolichestvoVoprosiv++; ?>
                    <tr>
                        <td colspan="3">
                            <p><strong>{{ $question['title'] }}</strong></p>
                            {{ $question['description'] }}
                        </td>
                    </tr>
                @if(count($question['answers']))
                    <?php $index = 1; ?>
                    @foreach($question['answers'] as $answer_is => $answer)
                    <tr>
                        <td><p align="center">{{ $index }}</p></td>
                        <td>{{ $answer['description'] }}</td>
                        <td>
                        @if($answer['correct'] == 1 && $answer['user_correct'] == 1)
                            <p align="center">Ваш ответ (верный)</p>
                            <?php $KolichestvoPravilnuhOtvetov++; ?>
                        @elseif($answer['correct'] == 0 && $answer['user_correct'] == 1)
                            <p align="center">Ваш ответ (неверный)</p>
                        @elseif($answer['correct'] == 1 && $answer['user_correct'] == 0)
                            <p align="center">Правильный ответ</p>
                        @endif
                        </td>
                    </tr>
                        <?php $index++; ?>
                    @endforeach
                @endif
            @endforeach
                </table>
            </tbody>
            <?php $TablicaResultatovAttestacii = ob_get_clean(); ?>
        @endif
        @if(isset($template) && File::exists($template))
            <?php require($template);?>
        @endif
    </main>
</body>
</html>
