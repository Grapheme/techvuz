<?php

return array(

    'fields' => function () {

        return array(
            'variables' => array(
                'title' => 'Список доступных переменных',
                'type' => 'textarea',
                'others' => array(
                    #'readonly' => 'readonly',
                    'class' => 'readonly',
                ),
            ),
            'content' => array(
                'title' => 'Содержание',
                'type' => 'textarea_redactor',
            ),
        );

    },

    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        return $menus;
    },
    'group_actions' => array(
        'moderator' => function() {
            return array(
                'dicval_create' => 0,
                'dicval_delete' => 0,
            );
        },
    ),

    'versions' => 1,

    'actions' => function($dic, $dicval) { },

    'hooks' => array(
        'after_update' => function ($dic, $dicval) {
            Event::fire('otredaktirovan-document', array(array('title'=>$dicval->title)));
        },
    ),

    'seo' => false,
);
