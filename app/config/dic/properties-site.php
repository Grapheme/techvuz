<?php

return array(

    'fields' => function () {
        return array(
            'property' => array(
                'title' => 'Значение',
                'type' => 'text',
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
                'dicval_edit' => 0,
                'dicval_delete' => 0,
            );
        },
    ),
    'actions' => function($dic, $dicval) { },
    'hooks' => array(),
    'seo' => false,
);
