<?php

return array(

    'feedback' => array(
        'address' => 'support@grapheme.ru',
    ),

    'driver' => 'sendmail',
    'host' => '',
    'port' => 465,
    'from' => array('address' => 'info@tehvuz.ru', 'name' => 'ТехВуз.рф'),
    'username' => '',
    'password' => '',

    'sendmail' => '/usr/sbin/sendmail -bs',
    'encryption' => 'ssl',

    'pretend' => FALSE
);

//'driver' => 'smtp',
//'host' => 'smtp.yandex.ru',
//'port' => 465,
//'from' => array('address' => 'dah-sl@yandex.ru', 'name' => 'ТехВуз.рф'),
//'username' => 'dah-sl@yandex.ru',
//'password' => '534msd8HHS',
//
//'sendmail' => '/usr/sbin/sendmail -bs',
//'encryption' => 'ssl',
