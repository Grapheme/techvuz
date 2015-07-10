<?php

return array(

    'feedback' => array(
        'address' => 'support@grapheme.ru',
    ),

    'driver' => 'smtp',
    'host' => 'smtp.yandex.ru',
    'port' => 465,
    'from' => array('address' => 'feedback@grapheme.ru', 'name' => 'ТехВуз.рф'),
    'username' => 'dah-sl@yandex.ru',
    'password' => '534msd8HHS',

    'sendmail' => '/usr/sbin/sendmail -bs',
    'encryption' => 'ssl',
);


