<?php

return array(

    'fields' => function () {

        return array(
            'document' => array(
                'title' => 'Документ',
                'type' => 'image',
            ),

            'description' => array(
                'title' => 'Описание',
                'type' => 'textarea_redactor',
            ),
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
