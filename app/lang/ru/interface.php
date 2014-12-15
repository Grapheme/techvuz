<?php

return array(

    'SIGNUP' => array(
        'success' => 'Вы зарегистрированы. Мы отправили на email ссылку для активации аккаунта.',
        'success_login' => 'Вы зарегистрированы. Мы отправили на email ссылку для активации аккаунта.<br>Чтобы подолжить работу перейдите в <a href="'.URL::to(AuthAccount::getStartPage()).'">Личный кабинет</a>',
        'email_exist' => 'Email уже зарегистрирован',
        'fail' => 'Неверно заполнены поля',
    ),

    'ACTIVATE' => array(
        'success' => 'Email успешно активирован',
        'fail' => 'Ошибка при активации',
    ),

    'SIGNUP_LISTENER' => array(
        'success' => 'Сотрудник зарегистрирован' ,
        'success_desc' => ' отправлена ссылка для активации аккаунта сотрудника, а также пароль от личного кабинета.',
        'next_operation_1' => 'Добавить нового сотрудника',
        'next_operation_2' => 'Продолжить покупку курсов',
        'email_exist' => 'Email уже зарегистрирован',
        'fail' => 'Неверно заполнены поля'
    ),

    'UPDATE_PROFILE_LISTENER' => array(
        'success' => 'Сохранено',
        'fail' => 'Неверно заполнены поля'
    ),

    'UPDATE_PROFILE_COMPANY' => array(
        'success' => 'Сохранено',
        'fail' => 'Неверно заполнены поля'
    ),

    'UPDATE_PROFILE_INDIVIDUAl' => array(
        'success' => 'Сохранено',
        'fail' => 'Неверно заполнены поля'
    ),

    'ACCOUNT_STATUS' => array(
        'active' => 'Ваша учетная запись активирована.',
        'blocked' => 'Ваша учетная запись заблокирована.',
        'not_active' => 'Ваша учетная запись не активирована.',
        'not_moderator_approve' => 'Учетная запись активирована, теперь ее проверяет администратор.',
        'not_active_few_day' => 'до блокировки учетной записи.',
        'blocked_approve' => 'Ознакомительный период завершен, учетная запись заблокирована.',
        'blocked_edit_profile' => 'Изменение регистрационных данных заблокировано до закрытия всех заказов. Для внесения изменений обратитесь к администрации сайта',
        'blocked_edit_listener_profile' => 'Изменение анкеты заблокировано до окончания обучения'
    ),

    'ACCOUNT_EMAIL_STATUS' => array(
        'active' => 'Ваш Email активирован!',
        'not_active' => 'Подтвердите email и сможете заказывать курсы. Ссылка отправлена на Вашу почту.',
        'repeated_sending' => '<a href="'.returnRoute('activation-repeated-sending-letter',null,'#').'">Отправить ссылку еще раз</a>.'
    ),

    'PASSWORD_RESTORE' => array(
        'success' => 'На указанный Email выслано письмо с инструкцией по сбросу пароля.',
        'fail' => 'Указанный Email не зарегистрирован!',
        'fail_send' => 'Возникла ошибка при отправке Email!',
    ),

    'PASSWORD_RESET' => array(
        'success' => 'Пароль сброшен. Можете авторизоваться под новым паролем.',
        'fail' => 'Сброс невозможен. Указанный Email не определен!',
        'fail_token' => 'Неверный Token!',
    ),

    'REPEATED_SENDING_LETTER' => array(
        'success' => 'Письмо успешно отправлено.',
    ),

    'DEFAULT' => array(
        'success_insert' => 'Добавлено',
        'success_save' => 'Сохранено',
        'success_remove' => 'Удалено',
        'success_change' => 'Изменено',
        'fail' => 'Неверно заполнены поля'
    ),

    'COMPANY_LISTENER_STUDY_TEST_FINISH' => array(

        'success_chapter_test' => '<p>Поздравляем, Вы прошли промежуточный тест.</p> <h4>Ваш результат: ',
        'success_course_test' => '<p>Поздравляем, Вы прошли итоговый тест.</p> <h4>Ваш результат: ',
        'fail' => '<p>Вы не прошли тест. Попробуйте снова.</p> <h4>Ваш результат: ',
        'empty_answers' => 'Не выбраны ответы!',
    ),
    'STUDY_PROGRESS' => array(
        '0' => 'Сотрудник не приступил к обучению',
        '1' => 'Сотрудник изучает лекции',
        '2' => 'Сотрудник пока не сдал итоговое тестирование',
        '3' => 'Сотрудник завершил обучение'
    ),
    'STUDY_PROGRESS_LISTENER' => array(
        '0' => 'Курс не изучался',
        '1' => 'Изучаются лекции',
        '2' => 'Осталось пройти итоговое тестирование',
        '3' => 'Обучение завершено'
    )
);