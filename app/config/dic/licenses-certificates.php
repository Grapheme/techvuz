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

            'slider_group' => array(
                'title' => 'Номер группы',
                'type' => 'text',
            ),
            'slider_main' => array(
                'no_label' => true,
                'title' => 'Главный слайд',
                'type' => 'checkbox',
                'label_class' => 'normal_checkbox',
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
