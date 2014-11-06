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
        'success' => 'Сотрудник зарегистрирован.' ,
        'success_desc' => 'Мы отправили на email сотрудника ссылку для активации его аккаунта.',
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

    'ACCOUNT_STATUS' => array(
        'active' => 'Ваш аккаунт активирован!',
        'blocked' => 'Ваш аккаунт заблокирован!',
        'not_active' => 'Ваш аккаунт не активирован!',
        #'not_active' => 'Необходимо подтвердить ваш email, или аккаунт будет заблокирован через 5 дней',
        'not_active_few_day' => 'Аккаунт будет заблокирован через',
        'blocked_approve' => 'Ознакомительный период завершен. Ваш аккаунт заблокирован!',
        'repeated_sending' => 'Для повторной отправки активационных данных нажмите на <a href="'.returnRoute('activation-repeated-sending-letter',null,'#').'">ссылку</a>'
    ),

    'PASSWORD_RESTORE' => array(
        'success' => 'На указанный Email выслано письмо с инструкцией по сбросу пароля.',
        'fail' => 'Указанный Email не зарегистрирован!',
        'fail_send' => 'Возникла ошибка при отправке Email!',
    ),

    'PASSWORD_RESET' => array(
        'success' => 'Пароль сброшен. Можете авторизоваться подновым паролем.',
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

        'success_chapter_test' => 'Поздравляем Вы прошли промежуточный тест. Ваш результат: ',
        'success_course_test' => 'Поздравляем Вы прошли итоговый тест. Ваш результат: ',
        'fail' => 'Вы не прошли тест. Попробуйте снова. Ваш результат: ',
        'empty_answers' => 'Не выбраны ответы!',
    )
);