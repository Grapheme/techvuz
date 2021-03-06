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

        'actions_history' => array(

            'general' => array(

                'user_id' => array(
                    'title' => 'ID пользователя',
                    'type' => 'text',
                ),
                'action_id' => array(
                    'title' => 'Событие',
                    'type' => 'select',
                    'values' => function(){
                        return Dic::valuesBySlug('actions_types')->lists('name','id');
                    }
                ),
                'title' => array(
                    'title' => 'Название',
                    'type' => 'text',
                ),
                'link' => array(
                    'title' => 'Ссылка на событие',
                    'type' => 'text',
                ),
                'created_time' => array(
                    'title' => 'Дата создания',
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
            ),
        ),

    ),
);
