<?php
/**
 * С помощью данного конфига можно добавлять собственные поля к объектам DicVal.
 * Для каждого словаря (Dic) можно задать индивидуальный набор полей (ключ массива fields).
 * Набор полей для словаря определяется по его системному имени (slug).
 *
 * Для каждого словаря можно определить набор "постоянных" полей (general)
 * и полей для мультиязычных версий записи (i18n).
 * Первые будут доступны всегда, вторые - только если сайт имеет больше чем 1 язык.
 *
 * Каждое поле представлено в наборе именем на форме (ключ массива) и набором свойств (поля массива по ключу).
 * Обязательно должен быть определен тип поля (type) и заголовок (title).
 * Также можно задать следующие свойства:
 * - default - значение поля по-умолчанию
 * - others - набор дополнительных произвольных свойств элемента, таких как class, style, placeholder и т.д.
 * - handler - функция-замыкание, вызывается для обработки значения поля после получения ИЗ формы, перед записью в БД. Первым параметром передается значение поля, вторым - существующий объект DicVal, к которому относится данное поле
 * - value_modifier - функция-замыкание, вызывается для обработки значения поля после получения значения из БД, перед выводом В форму
 * - after_save_js - JS-код, который будет выполнен после сохранения страницы
 * - content - содержимое, которое будет выведено на экран, вместо генерации кода элемента формы
 * - label_class - css-класс родительского элемента
 *
 * Некоторые типы полей могут иметь свои собственные уникальные свойства, например: значения для выбора у поля select; accept для указания разрешенных форматов у поля типа file и т.д.
 *
 * [!] Вывод полей на форму происходит с помощью /app/lib/Helper.php -> Helper::formField();
 *
 * На данный момент доступны следующие поля:
 * - text
 * - textarea
 * - textarea_redactor (доп. JS)
 * - date (не требует доп. JS, работает для SmartAdmin из коробки, нужны handler и value_modifier для обработки)
 * - image (использует ExtForm::image() + доп. JS)
 * - gallery (использует ExtForm::gallery() + доп. JS, нужен handler для обработки)
 * - upload
 * - video
 *
 * Типы полей, запланированных к разработке:
 * - select
 * - checkbox
 * - radio
 * - upload-group
 * - video-group
 *
 * Также в планах - возможность активировать SEO-модуль для каждого словаря по отдельности (ключ массива seo) и обрабатывать его.
 *
 * [!] Для визуального разделения можно использовать следующий элемент массива: array('content' => '<hr/>'),
 *
 * @author Zelensky Alexander
 *
 */
return array(

    'fields' => array(

        'quests' => array(

            'general' => array(

                'short' => array(
                    'title' => 'Краткое описание',
                    'type' => 'textarea_redactor',
                ),

                'date_start' => array(
                    'title' => 'Дата начала сбора',
                    'type' => 'date',
                    'others' => array(
                        'class' => 'text-center',
                        'style' => 'width: 221px',
                        'placeholder' => 'Нажмите для выбора'
                    ),
                    'handler' => function($value) {
                            return $value ? @date('Y-m-d', strtotime($value)) : $value;
                        },
                    'value_modifier' => function($value) {
                            return $value ? @date('d.m.Y', strtotime($value)) : $value;
                        },
                ),
                'date_stop' => array(
                    'title' => 'Дата окончания сбора',
                    'type' => 'date',
                    'others' => array(
                        'class' => 'text-center',
                        'style' => 'width: 221px',
                        'placeholder' => 'Нажмите для выбора'
                    ),
                    'handler' => function($value) {
                            return $value ? @date('Y-m-d', strtotime($value)) : $value;
                        },
                    'value_modifier' => function($value) {
                            return $value ? @date('d.m.Y', strtotime($value)) : $value;
                        },
                ),
                'date_quest' => array(
                    'title' => 'Дата проведения квеста',
                    'type' => 'date',
                    'others' => array(
                        'class' => 'text-center',
                        'style' => 'width: 221px',
                        'placeholder' => 'Нажмите для выбора'
                    ),
                    'handler' => function($value) {
                            return $value ? @date('Y-m-d', strtotime($value)) : $value;
                        },
                    'value_modifier' => function($value) {
                            return $value ? @date('d.m.Y', strtotime($value)) : $value;
                        },
                ),

                'target_amount' => array(
                    'title' => 'Целевая сумма сбора',
                    'type' => 'text',
                ),
                'current_amount' => array(
                    'title' => 'Собранно на данный момент',
                    'type' => 'text',
                ),
                'count_members' => array(
                    'title' => 'Количество участников',
                    'type' => 'text',
                ),

                array('content' => '<hr/>'),

                'link_to_file_print' => array(
                    'title' => 'Файл принта',
                    'type' => 'upload',
                    'accept' => '*', # .exe,image/*,video/*,audio/*
                    'label_class' => 'input-file',
                    'handler' => function($value, $element = false) {
                            if (@is_object($element) && @is_array($value)) {
                                $value['module'] = 'dicval';
                                $value['unit_id'] = $element->id;
                            }
                            return ExtForm::process('upload', $value);
                        },
                ),

                array('content' => '<hr/>'),

                'link_to_buy_shirt' => array(
                    'title' => 'УРЛ для покупки футболки',
                    'type' => 'text',
                    'others' => array(
                        'placeholder' => 'http://'
                    ),
                ),
                'photo' => array(
                    'title' => 'Фото',
                    'type' => 'image',
                ),

                array('content' => '<hr/>'),

                'video' => array(
                    'title' => 'Видео',
                    'type' => 'video',
                    'handler' => function($value, $element = false) {
                            if (@is_object($element) && @is_array($value)) {
                                $value['module'] = 'dicval';
                                $value['unit_id'] = $element->id;
                            }
                            return ExtForm::process('video', $value);
                        },
                ),

                array('content' => '<hr/>'),

                'description' => array(
                    'title' => 'Полное описание',
                    'type' => 'textarea_redactor',
                ),

            ),
        ),

        'members' => array(

            'general' => array(

                'fio' => array(
                    'title' => 'Фамилия Имя',
                    'type' => 'text',
                    'others' => array(
                        #'disabled',
                    ),
                ),
                'payment_date' => array(
                    'title' => 'Дата платежа',
                    'type' => 'text',
                    'others' => array(
                        'disabled',
                    ),
                ),
                'payment_amount' => array(
                    'title' => 'Сумма платежа',
                    'type' => 'text',
                    'others' => array(
                        'disabled',
                    ),
                ),
                'payment_method' => array(
                    'title' => 'Интерфейс платежа',
                    'type' => 'text',
                    'others' => array(
                        'disabled',
                    ),
                ),
            ),
        ),

        /*
            array(
                'price' => array(
                    'title' => 'Текстовое поле',
                    'type' => 'text',
                ),
                'short' => array(
                    'title' => 'textarea обычная',
                    'type' => 'textarea',
                ),
                'description' => array(
                    'title' => 'textarea html-разметкой',
                    'type' => 'textarea_redactor',
                ),

                'gallery' => array(
                    'title' => 'Галерея',
                    'type' => 'gallery',
                    'handler' => function($array, $element) {
                            return ExtForm::process('gallery', array(
                                'module'  => 'dicval_meta',
                                'unit_id' => $element->id,
                                'gallery' => $array,
                                'single'  => true,
                            ));
                        }
                ),
            ),

        */

    ),

    'seo' => array(
        'number_type' => 0,
    ),
);
