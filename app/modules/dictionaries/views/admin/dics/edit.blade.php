@extends(Helper::acclayout())


@section('style')
@stop


@section('content')

    <?
    $create_title = "Редактировать запись:";
    $edit_title   = "Добавить запись:";

    $url        = 
        @$element->id
        ? action('dic.update', array('id' => $element->id))
        : action('dic.store');
    $method     = @$element->id ? 'PUT' : 'POST';
    $form_title = @$element->id ? $create_title : $edit_title;
    ?>

    @include($module['tpl'].'/menu')

    {{ Form::model($element, array('url'=>$url, 'class'=>'smart-form', 'id'=>$module['entity'].'-form', 'role'=>'form', 'method'=>$method)) }}

    <!-- Fields -->
	<div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">
                <header>{{ $form_title }}</header>
                <fieldset>

                    <section>
                        <label class="label">Системное имя</label>
                        <label class="input">
                            {{ Form::text('slug', null, array('placeholder' => 'system_name')) }}
                        </label>
                    </section>

                    <section>
                        <label class="label">Название</label>
                        <label class="input">
                            {{ Form::text('name', null, array('placeholder' => 'Название словаря')) }}
                        </label>
                    </section>

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


        @if (Allow::action($module['group'], 'settings'))
        <section class="col col-6">
            <div class="well">
                <header>Настройки словаря</header>
                <fieldset>

                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('entity', 1) }}
                            <i></i>
                            <strong>Вынести словарь в отдельную сущность</strong>
                        </label>
                    </section>

                    <section>
                        <label class="label">Класс иконки <a href="http://fontawesome.io/icons/" target="_blank">FontAwesome</a></label>
                        <label class="input">
                            {{ Form::text('icon_class', null, array('placeholder' => 'Например: fa-circle-o')) }}
                        </label>
                    </section>

                </fieldset>
                <fieldset>

                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('hide_slug', 1) }}
                            <i></i>
                            Скрыть поле для ввода системного имени (slug)
                        </label>

                        @if (0)
                        <label class="checkbox">
                            {{ Form::checkbox('make_slug_from_name', 1) }}
                            <i></i>
                            Если не задано Системное имя - <abbr title="Транслит от названия">генерировать автоматически</abbr>
                        </label>
                        @endif

                        <label class="label">
                            Генерация "Системного имени" из Названия:
                        </label>
                        <label class="select margin-bottom-5">
                            {{ Form::select('make_slug_from_name', array(
                                '0' => 'Не генерировать',
                                'Транслит + нижний регистр' => array(
                                    '1' => 'Всегда (даже если поле скрыто или передано непустое значение)',
                                    '2' => 'Поле не скрыто, передано пустое значение',
                                    '3' => 'Поле скрыто, в БД пустое значение (только 1 раз)',
                                ),
                                'Транслит + сохранять верхний регистр' => array(
                                    '4' => 'Всегда (даже если поле скрыто или передано непустое значение)',
                                    '5' => 'Поле не скрыто, передано пустое значение',
                                    '6' => 'Поле скрыто, в БД пустое значение (только 1 раз)',
                                ),
                                'Без транслита' => array(
                                    '7' => 'Всегда (даже если поле скрыто или передано непустое значение)',
                                    '8' => 'Поле не скрыто, передано пустое значение',
                                    '9' => 'Поле скрыто, в БД пустое значение (только 1 раз)',
                                ),
                            )) }}
                        </label>

                    </section>

                    <section>
                        <label class="label">Заголовок поля name</label>
                        <label class="input">
                            {{ Form::text('name_title') }}
                        </label>
                    </section>

                    <section>
                        <label class="label">Кто может видеть этот словарь:</label>
                        <label class="radio">
                            {{ Form::radio('view_access', 0, $element->id ? NULL : true) }}
                            <i></i> Все, у кого есть доступ к словарям
                        </label>
                        <label class="radio">
                            {{ Form::radio('view_access', '2') }}
                            <i></i> Только те, кто может видеть скрытые словари
                        </label>
                        <label class="radio">
                            {{ Form::radio('view_access', '1') }}
                            <i></i> Только Разработчики
                        </label>
                    </section>

                </fieldset>
                <fieldset>

                    <section class="clearfix">
                        <label class="label">Кол-во элементов на страницу (0 - пагинация отключена)</label>
                        <label class="input" style="width:100px">
                            {{ Form::text('pagination', $element->id ? NULL : 0) }}
                        </label>
                    </section>

                    <section class="clearfix">
                        <label class="label">Сортировать содержимое по полю</label>
                        <label class="select margin-bottom-5">
                            {{ Form::select('sort_by', array(
                                'order' => 'По умолчанию',
                                'name' => 'Название',
                                'slug' => 'Системное имя',
                                'created_at' =>
                                'Время создания',
                                'updated_at' => 'Время последнего изменения'
                            ) + (array)@$dic_fields_array) }}
                        </label>
                        <label class="radio pull-left margin-right-10">
                            {{ Form::radio('sort_order_reverse', '0', $element->id ? NULL : true) }}
                            <i></i> По возрастанию
                        </label>
                        <label class="radio pull-left margin-right-10">
                            {{ Form::radio('sort_order_reverse', '1') }}
                            <i></i> По убыванию
                        </label>
                    </section>

                    <section>
                        <label class="note">
                            Следующая возможность будет работать только если отключена пагинация и сортировка осуществляется по умолчанию (по полю order)
                        </label>
                        <label class="checkbox">
                            {{ Form::checkbox('sortable', 1, $element->id ? NULL : true) }}
                            <i></i>
                            Возможность менять порядок элементов перетаскиванием
                        </label>
                    </section>

                </fieldset>
            </div>
        </section>
        @endif

        <!-- /Form -->
   	</div>

    @if(@$element->id)
    @else
    {{ Form::hidden('redirect', action('dic.index')) }}
    @endif

    {{ Form::close() }}

@stop


@section('scripts')
    <script>
    var essence = '{{ $module['entity'] }}';
    var essence_name = '{{ $module['entity_name'] }}';
	var validation_rules = {
        name:              { required: true },
        slug:              { required: true },
	};
	var validation_messages = {
        name:              { required: "Укажите название" },
        slug:              { required: "Укажите системное имя" },
	};
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

@stop