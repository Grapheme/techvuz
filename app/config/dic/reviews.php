<?php

return array(

    'fields' => function () {

        return array(
            'user_avatar' => array(
                'title' => 'Аватар',
                'type' => 'image',
            ),
            'user_position' => array(
                'title' => 'Должность',
                'type' => 'text',
            ),
            'description' => array(
                'title' => 'Содержание',
                'type' => 'textarea_redactor',
            ),
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        return $menus;
    },


    'actions' => function($dic, $dicval) { },

    'hooks' => array(
        'after_store' => function ($dic, $dicval) {
            Event::fire('dobavlen-otzuv', array(array('title'=>$dicval->title)));
        },
        'after_update' => function ($dic, $dicval) {
            Event::fire('otredaktirovan-otzuv', array(array('title'=>$dicval->title)));
        },
        'after_destroy' => function ($dic, $dicval) {
            Event::fire('udalen-otzuv', array(array('title'=>$dicval->title)));
        },
    ),

    'seo' => false,
);
