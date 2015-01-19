<?php

return array(

    'fields' => function () {

        return array(
            #'variables' => array(
            #    'title' => 'Список доступных переменных',
            #    'type' => 'textarea',
            #    'others' => array(
            #        #'readonly' => 'readonly',
            #        'class' => 'readonly',
            #    ),
            #),
            'content' => array(
                'title' => 'Содержание',
                'type' => 'textarea_redactor',
            ),
            'word_template' => array(
                'title' => 'Шаблон Microsoft WORD 2007 и выше(.docx).',
                'type' => 'upload',
                'label_class' => 'input-file',
                'others' => [
                    'id' => 'select-template',
                    'accept' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ],
                'handler' => function($value, $element = false) {
                    if (@is_object($element) && @is_array($value)) {
                        $value['module'] = 'dicval';
                        $value['unit_id'] = $element->id;
                    }
                    return ExtForm::process('upload', $value);
                },
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
    'custom_validation' => <<<JS
    var validation_rules = {
		'name': { required: true },
		'fields[word_template][file]': { accept: "application/vnd.openxmlformats-officedocument.wordprocessingml.document", filesize: 10485760 }
	};
	var validation_messages = {
		'name': { required: "Укажите название" },
		'fields[word_template][file]': { accept: "Только файлы DOCX", filesize: "Максимальный размер файла - 10 Mb" },
	};
JS
);
