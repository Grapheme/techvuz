<?php

return array(

    'fields' => function () {
        return array(
            'content' => array(
                'title' => 'Содержание',
                'type' => 'textarea_redactor',
            ),
            'active' => array(
                'no_label' => FALSE,
                'title' => 'Активный',
                'type' => 'checkbox',
            )
        );
    },
    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        return $menus;
    },
    'actions' => function($dic, $dicval) { },
    'hooks' => array(),
    'seo' => false,
);
