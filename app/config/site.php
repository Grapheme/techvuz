<?php

return array(

    'paginate_limit' => 30,

    'news_count_on_page' => 5,
    'news_template' => 'news-list',
    'articles_count_on_page' => 5,
    'articles_template' => 'articles-list',
    'catalog_count_on_page' => 20,
    'catalog_template' => 'catalog',


    'upload_dir' => '/uploads',

    'galleries_photo_dir' => public_path('uploads/galleries'),
    'galleries_thumb_dir' => public_path('uploads/galleries/thumbs'),

    'galleries_photo_public_dir' => '/uploads/galleries',
    'galleries_thumb_public_dir' => '/uploads/galleries/thumbs',

    'galleries_photo_size' => -800, # 800 => 800x600 || 600x800 ; -800 => 800x1000 || 1000x800
    'galleries_thumb_size' => -265, # 200 => 200x150 || 150x200 ; -200 => 200x300 || 300x200

    ## Disable functionality of changing url "on-the-fly" for generating
    ## seo-friendly url (via URL::route('page', '...')) with right various url-segments for multilingual pages.
    'disable_url_modification' => 0,

    ## Directory for module UPLOADS
    'uploads_dir' => public_path('uploads/files'),
);
