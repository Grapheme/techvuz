<?php

return array(

    #'seo' => 1,

    'versions' => 0,

    'disable_mainpage_route' => false, ## отключить маршрут главной страницы (mainpage)

    'disable_url_modification' => true, ## отключить модификаторы урлов. Не включать!
    'disable_slug_to_template' => true, ## отключить автоматический поиск шаблона страницы по ее системному имени в случае, если страница не существует

    'preload_pages_limit' => 0, ## NULL - never; 0 - always; 100 - if less than 100 (+one more sql request)
    'preload_cache_lifetime' => 60*24, ## время жизни кеша страниц, в минутах

);