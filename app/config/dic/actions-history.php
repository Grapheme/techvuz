<?php

return array(

    'fields' => function () {
        return array();
    },
    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        return $menus;
    },
    'group_actions' => array(
        'moderator' => function() {
            return array(
                'dicval_create' => 0,
            );
        },
    ),
    'actions' => function($dic, $dicval) { },
    'hooks' => array(),
    'seo' => false,
);
