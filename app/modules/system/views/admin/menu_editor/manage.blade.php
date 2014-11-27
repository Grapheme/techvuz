@extends(Helper::acclayout())


@section('style')
<style>
.menu_list_item_dd-handle_custom {
    min-height: 42px;
    height: 42px;
}
.menu_list_item_dd-handle_custom:before {
    line-height: 38px !important;
}
.panel-title-custom {
    margin-left: 27px !important;
}
.panel-title-custom a {
    line-height: 22px;
}
</style>
@stop


@section('content')

    <?
    #$create_title = "Редактировать меню:";
    #$edit_title   = "Добавить меню:";

    $url = action($module['name'] . '.update', array('id' => $element->id));
    $method     = @$element->id ? 'PUT' : 'POST';
    #$form_title = @$element->id ? $create_title : $edit_title;
    $form_title = 'Пункты меню';
    ?>

    @include($module['tpl'].'menu')

    {{ Form::model($element, array('url' => $url, 'class' => 'smart-form', 'id' => $module['entity'].'-form', 'role' => 'form', 'method' => $method, 'files' => true)) }}

	<div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">

                <header>{{ $form_title }}</header>
<!--
<div class="dd">
    <ol class="dd-list">
        <li class="dd-item" data-id="1">
            <div class="dd-handle">Item 1</div>
        </li>
        <li class="dd-item" data-id="2">
            <div class="dd-handle">Item 2</div>
        </li>
        <li class="dd-item" data-id="3">
            <div class="dd-handle">Item 3</div>
            <ol class="dd-list">
                <li class="dd-item" data-id="4">
                    <div class="dd-handle">Item 4</div>
                </li>
                <li class="dd-item" data-id="5">
                    <div class="dd-handle">Item 5</div>
                </li>
            </ol>
        </li>
    </ol>
</div>
-->
                <fieldset>
                    <section class="dd">

                        <p class="alert alert-info fade in padding-10 menu_list_info">
                            <i class="fa-fw fa fa-info"></i>
                            Добавьте пункты в меню, используя боковую панель.
                        </p>

                        <ol class="menu_items dd-list">
                        </ol>

                    </section>

                    <textarea name="order" id="nestable-output" rows="3" class="form-control font-md hidden"></textarea>

                </fieldset>

                <footer>
                    <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                        <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                    </a>
                    <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                        <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                    </button>
                </footer>

		    </div>
    	</section>


        <section class="col col-6">
            <div class="well">
                <header>Добавить новый пункт</header>

                <fieldset>
                    <section>

                        <div class="widget-body">

                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a href="#s1" data-toggle="tab">
                                        Страница
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#s2" data-toggle="tab">
                                        Ссылка
                                    </a>
                                </li>
                                <li class="dropdown pull-right">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" title="Для тонкой настройки системным администратором. Если Вы не знаете, что произойдет в результате Ваших действий в этом разделе - не меняйте здесь ничего.">
                                        <i class="fa fa-gear"></i>
                                        Еще
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#s3" data-toggle="tab">Маршрут</a>
                                        </li>
                                        <li>
                                            <a href="#s4" data-toggle="tab">Функция</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                            <div id="myTabContent1" class="tab-content padding-10">

                                <div class="tab-pane fade active in" id="s1">

                                    @if (!count($pages))
                                        <p class="alert alert-warning fade in padding-10">
                                            <i class="fa-fw fa fa-warning"></i>
                                            Нет страниц для добавления в меню
                                        </p>
                                    @else
                                        <label class="select">
                                            {{ Form::select('page_id', $pages->lists('name', 'id')) }}
                                        </label>

                                        <label class="margin-top-10">
                                            <a href="#" class="btn btn-default add_to_menu add_to_menu_page">
                                                <i class="fa fa-angle-left"></i>
                                                Добавить в меню
                                            </a>
                                        </label>
                                    @endif

                                </div>


                                <div class="tab-pane fade" id="s2">

                                    <label class="label">
                                        URL
                                    </label>
                                    <label class="input margin-bottom-10">
                                        {{ Form::text('link_url', 'http://', array('placeholder' => 'Произвольный URL-адрес')) }}
                                    </label>

                                    <label class="label">
                                        Текст
                                    </label>
                                    <label class="input">
                                        {{ Form::text('link_text', '', array('placeholder' => 'Текст элемента меню')) }}
                                    </label>

                                    <label class="margin-top-10">
                                        <a href="#" class="btn btn-default add_to_menu add_to_menu_link">
                                            <i class="fa fa-angle-left"></i>
                                            Добавить в меню
                                        </a>
                                    </label>

                                </div>


                                <div class="tab-pane fade" id="s3">

                                    <p class="alert alert-warning fade in padding-10 margin-bottom-15">
                                        <i class="fa-fw fa fa-info-circle"></i>
                                        Маршруты заложены в системе и могут генерировать ссылки на основе переданных параметров, или без них.<br/>
                                        <i class="fa-fw fa fa-warning"></i>
                                        Если Вы не знаете, как использовать маршруты - не добавляйте их в меню.
                                    </p>

                                    <label class="label">
                                        Название маршрута
                                    </label>
                                    <label class="input margin-bottom-10">
                                        {{ Form::text('route_name', '', array('placeholder' => 'Название именованного роута')) }}
                                    </label>

                                    <label class="label">
                                        Параметры маршрута
                                    </label>
                                    <label class="textarea">
                                        {{ Form::textarea('route_params', '', array('placeholder' => 'Параметры именованного роута, каждый с новой строки')) }}
                                    </label>
                                    <label class="note">
                                        Для передачи именованных параметров используйте знак "равно", например:<br/>
                                        newslist<br/>sort_by=title<br/>sort_type=asc
                                    </label>

                                    <label class="margin-top-10">
                                        <a href="#" class="btn btn-default add_to_menu add_to_menu_route">
                                            <i class="fa fa-angle-left"></i>
                                            Добавить в меню
                                        </a>
                                    </label>
                                </div>


                                <div class="tab-pane fade" id="s4">

                                    <p class="alert alert-warning fade in padding-10 margin-bottom-15">
                                        <i class="fa-fw fa fa-info-circle"></i>
                                        Функции-обработчики создаются разработчиком на этапе проектирования системы.<br/>
                                        <i class="fa-fw fa fa-warning"></i>
                                        Если Вы не знаете, как использовать функции-обработчики - не добавляйте их в меню.
                                    </p>

                                    @if (count($functions))
                                    <label class="label">
                                        Функция-обработчик
                                    </label>
                                    <label class="select">
                                        {{ Form::select('function_name', $functions) }}
                                    </label>
                                    @else
                                    Нет объявленных функций-обработчиков.
                                    @endif

                                    <label class="note">
                                        Функции-обработчики определяются в конфигурационном файле меню (menu.functions)
                                    </label>

                                    @if (count($functions))
                                    <label class="margin-top-10">
                                        <a href="#" class="btn btn-default add_to_menu add_to_menu_function">
                                            <i class="fa fa-angle-left"></i>
                                            Добавить в меню
                                        </a>
                                    </label>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </section>
                </fieldset>

                <footer>
                    {{--
                    <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                        <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                    </a>
                    <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                        <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                    </button>
                    --}}
                </footer>
            </div>

        </section>

        <!-- /Form -->
   	</div>

    @if(@$element->id)
    @else
    {{ Form::hidden('redirect', action($module['name'].'.index') . (Request::getQueryString() ? '?' . Request::getQueryString() : '')) }}
    @endif

    {{ Form::close() }}


    {{-- Helper::dd(Helper::getLayoutProperties()); --}}


    <div id="templates" class="hidden">


        <div class="childrens">
            <ol class="dd-list">
                %block%
            </ol>
        </div>


        <div class="main">

            <li class="dd-item dd3-item" data-id="%N%">

                <div class="dd-handle dd3-handle menu_list_item_dd-handle_custom">
                    Drag
                </div>
                <div class="panel-group smart-accordion-default margin-top-10 margin-bottom-10 menu_item sortable_item">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title panel-title-custom">
                                <a data-toggle="collapse" data-parent="#accordion" href="#menu_item_%N%" class="collapsed">
                                    <i class="fa fa-lg fa-angle-down pull-right margin-top-5 accordion-collapse"></i>
                                    <i class="fa fa-lg fa-angle-up pull-right margin-top-5 accordion-collapse"></i>
                                    <span class="menu_item_title">%title%</span>&nbsp;
                                    <span class="pull-right txt-color-grayDark">%mark%</span>
                                </a>
                            </h4>
                        </div>
                        <div id="menu_item_%N%" class="panel-collapse collapse bg-color-white" style="height: 0px;">
                            <div class="panel-body padding-10 menu_item_type_content">

                                %inner%

                                {{ Form::hidden('items[%N%][id]', '%N%') }}

                                <hr class="simple" />

                                <span class="pull-left">
                                    <label class="checkbox">
                                        <input type="checkbox" name="items[%N%][target]" value="_blank" %target_blank% />
                                        <i></i>
                                        Открывать в новом окне
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="items[%N%][hidden]" value="1" %is_hidden% />
                                        <i></i>
                                        Скрыть
                                    </label>
                                </span>

                                <span class="pull-right">
                                    <a href="#" class="btn btn-danger btn-sm delete_menu_item">
                                        <i class="fa fa-trash-o"></i>
                                        Удалить
                                    </a>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>

                %childrens%

            </li>

        </div>


        <div class="page">

            <label class="label">
                Текст ссылки
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][text]', '%text%', array('class' => 'text_for_title')) }}
            </label>

            <label class="label">
                Атрибут title
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][title]', '%attr_title%') }}
            </label>

            <label class="label margin-bottom-10">
                Оригинал страницы:
                <a href="{{ URL::route('page', '++page_id++') }}" target="_blank">просмотреть</a> или
                <a href="{{ URL::route('page.edit', '++page_id++') }}" target="_blank">редактировать</a>
            </label>

            {{ Form::hidden('items[%N%][type]', 'page') }}
            {{ Form::hidden('items[%N%][page_id]', '%page_id%') }}
            {{ Form::hidden('null', '<без названия>', array('class' => 'default_text_for_title')) }}

        </div>


        <div class="link">

            <label class="label">
                URL
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][url]', '%url%', array('class' => 'default_text_for_title')) }}
            </label>

            <label class="label">
                Текст ссылки
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][text]', '%text%', array('class' => 'text_for_title')) }}
            </label>

            <label class="label">
                Атрибут title
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][title]', '%attr_title%') }}
            </label>

            {{ Form::hidden('items[%N%][type]', 'link') }}

        </div>


        <div class="route">

            <label class="label">
                Название маршрута
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][route_name]', '%route_name%', array('class' => 'default_text_for_title')) }}
            </label>

            <label class="label">
                Параметры маршрута
            </label>
            <label class="textarea margin-bottom-10">
                {{ Form::textarea('items[%N%][route_params]', '%route_params%', array('class' => 'default_text_for_title')) }}
            </label>

            <label class="label">
                Текст ссылки
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][text]', '%text%', array('class' => 'text_for_title')) }}
            </label>

            <label class="label">
                Атрибут title
            </label>
            <label class="input margin-bottom-10">
                {{ Form::text('items[%N%][title]', '%attr_title%') }}
            </label>

            {{ Form::hidden('items[%N%][type]', 'route') }}
            {{ Form::hidden('null', '<без названия>', array('class' => 'default_text_for_title')) }}

        </div>


        <div class="function">

            <div class="menu_item_function_parameters">

                <label class="label">
                    Текст ссылки
                </label>
                <label class="input margin-bottom-10">
                    {{ Form::text('items[%N%][text]', '%text%', array('class' => 'text_for_title')) }}
                </label>

                <label class="label">
                    Атрибут title
                </label>
                <label class="input margin-bottom-10">
                    {{ Form::text('items[%N%][title]', '%attr_title%') }}
                </label>

                <label class="label margin-bottom-10">
                    Функция: %function_name%
                </label>

            </div>

            <label class="checkbox">
                <input type="checkbox" name="items[%N%][use_function_data]" value="1" class="use_function_data" %use_function_data% />
                <i></i>
                Использовать данные из функции
            </label>
            <label class="note">
                Если отмечена данная опция, то текст ссылки, ее url и прочие данные будут браться непосредственно из самой функции, без учета указанных данных.
            </label>

            {{ Form::hidden('null', '<без названия>', array('class' => 'default_text_for_title')) }}
            {{ Form::hidden('items[%N%][type]', 'function') }}
            {{ Form::hidden('items[%N%][function_name]', '%function_name%') }}

        </div>

    </div>

    <script>
    var nesting_level = {{ $element->nesting_level ?: 5 }};
    </script>
@stop


@section('scripts')
    <script>
    var essence = '{{ $module['entity'] }}';
    var essence_name = '{{ $module['entity_name'] }}';
	var validation_rules = {
		name:  { required: true },
	};
	var validation_messages = {
		name: { required: "Укажите системное имя" },
	};
    </script>

    <script>
        var onsuccess_function = function() {}
    </script>

	{{ HTML::script('js/modules/standard.js') }}

	<script type="text/javascript">
		if(typeof pageSetUp === 'function'){pageSetUp();}
		if(typeof runFormValidation === 'function') {
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}", runFormValidation);
		} else {
			loadScript("{{ asset('js/vendor/jquery-form.min.js'); }}");
		}
	</script>

    {{-- HTML::script('private/js/plugin/jquery-nestable/jquery.nestable.min.js') --}}

	<script>
        var menu_items_loaded = false;

        @if (isset($element->items) && count($element->items))

            /**
             * Add items to menu area
             */

            <?
            /*
            function show_menu($list, $element, $new_level = false) {
                foreach ($list as $l => $lst) {
                    ## Show current item
                    $item = $element->items->{$lst['id']};
                    #Helper::dd($item);
                    show_menu_item($item, $new_level);

                    ## Show child items
                    if (isset($lst['children']) && count($lst['children'])) {
                        #Helper::dd($lst['children']);
                        #$list = json_decode($element->order, 1);
                        show_menu($lst['children'], $element, true);
                    }
                }
            }
            function show_menu_item($item, $new_level) {
                echo "menu_editor.add_menu_item('" . $item->type . "', " . json_encode($item) . ", " . (int)$new_level . ");\n";
            }
            if ($element->order) {
                $items_order = json_decode($element->order, 1);
                ##Helper::dd($items_order);
                show_menu($items_order, $element);
            }
            */
            ?>

            @if ($element->order)
                menu_editor.show_menu({{ $element->order ?: '{}' }}, {{ json_encode($element->items) ?: '{}' }});
            @endif

            {{--
            @foreach($element->items as $i => $item)
                menu_editor.add_menu_item('{{ $item->type }}', {{ json_encode($item) }});
            @endforeach
            --}}

            /**
             * Get max id of the exists menu items
             */
            //console.log($('.dd [name^=items][name$="[id]"]'));
            var ids= $('.dd [name^=items][name$="[id]"]').map(function() {
                return $(this).val();
            }).get();
            var N = Array.max(ids);
            //alert("Max val: " + Array.max(ids));
        @endif

        @if ($element->order)
            $('.dd').data('output').text('{{ $element->order }}');
        @else
            updateOutput($('.dd'));
        @endif

        //$('.menu_list_item_dd-handle_custom').css('height', $('.panel-default').css('height'));

        menu_items_loaded = true;

	</script>

@stop

