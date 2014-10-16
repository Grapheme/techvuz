@extends(Helper::acclayout())


@section('style')
@stop


@section('content')

    <?
    $create_title = "Редактировать меню:";
    $edit_title   = "Добавить меню:";

    $url =
        @$element->id
        ? action($module['name'] . '.update', array('id' => $element->id))
        : action($module['name'] . '.store',  array());
    $method     = @$element->id ? 'PUT' : 'POST';
    $form_title = @$element->id ? $create_title : $edit_title;
    ?>

    @include($module['tpl'].'menu')

    {{ Form::model($element, array('url' => $url, 'class' => 'smart-form', 'id' => $module['entity'].'-form', 'role' => 'form', 'method' => $method, 'files' => true)) }}

	<div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">

                <header>{{ $form_title }}</header>

                <fieldset>
                    <section>
                        <label class="label">Системное имя</label>
                        <label class="input">
                            {{ Form::text('name') }}
                        </label>
                    </section>

                    <section>
                        <label class="label">Название</label>
                        <label class="input">
                            {{ Form::text('title') }}
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


    	<section class="col col-6">
            <div class="well">
                <header role="heading">
                    Дополнительно
                </header>

                <fieldset>

                    <section>
                        <label class="label">Максимальное кол-во уровней вложенности</label>
                        <label class="select">
                            {{ Form::select('nesting_level', array(5=>5, 4=>4, 3=>3, 2=>2, 1=>'1 (без вложенности)')) }}
                        </label>
                    </section>


                    <?
                    $placements = @Helper::getLayoutProperties()['MENU_PLACEMENTS'];
                    ?>
                    @if (isset($placements) && is_array($placements) && count($placements))
                    <hr class="simple" />
                    {{ Form::hidden('update_placements', 1) }}
                    <section class="clearfix margin-top-15">
                        <label class="label">
                            Выберите предустановленные места в шаблоне, в которых будет отображаться данное меню:
                        </label>
                        @foreach ($placements as $placement_key => $placement_name)
                            <label class="checkbox col col-xs-12 col-sm-12  col-md-12 col-lg-12">
                                {{ Form::checkbox('placements[]', $placement_key, (isset($menu_placement[$placement_key]) && $menu_placement[$placement_key] != '' && $menu_placement[$placement_key] == $element->name)) }}
                                <i></i>
                                {{ $placement_name }} <em class="note">{{ @$menus[$menu_placement[$placement_key]] }}</em>
                            </label>
                        @endforeach
                    </section>
                    <hr class="simple" />
                    @endif

                    <section>
                        <div class="panel-group smart-accordion-default margin-bottom-10">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#additional_1" class="collapsed">
                                            <i class="fa fa-lg fa-angle-down pull-right margin-top-5 accordion-collapse"></i>
                                            <i class="fa fa-lg fa-angle-up pull-right margin-top-5 accordion-collapse"></i>
                                            <span class="">Шаблоны</span>
                                        </a>
                                    </h4>
                                </div>

                                <div id="additional_1" class="panel-collapse collapse bg-color-white">
                                    <div class="panel-body padding-10 menu_item_type_content">

                                        <section>
                                            <label class="label">
                                                Контейнер меню
                                            </label>
                                            <label class="input">
                                                {{ Form::text('container', $element->container ?: '&lt;ul>%elements%&lt;/ul>') }}
                                            </label>
                                            <label class="note margin-bottom-10">
                                                По умолчанию: &lt;ul>%elements%&lt;/ul>
                                            </label>
                                        </section>

                                        <section>
                                            <label class="label">
                                                Контенер элемента
                                            </label>
                                            <label class="input">
                                                {{ Form::text('element_container', $element->element_container ?: '&lt;li%attr%>%element%%children%&lt;/li>') }}
                                            </label>
                                            <label class="note margin-bottom-10">
                                                По умолчанию: &lt;li%attr%>%element%%children%&lt;/li>
                                            </label>
                                        </section>

                                        <section>
                                            <label class="label">
                                                Шаблон элемента
                                            </label>
                                            <label class="input">
                                                {{ Form::text('element', $element->element ?: '&lt;a href="%url%"%attr%>%text%&lt;/a>') }}
                                            </label>
                                            <label class="note margin-bottom-10">
                                                По умолчанию: &lt;a href="%url%"%attr%>%text%&lt;/a>
                                            </label>
                                        </section>

                                        <section>
                                            <label class="label">
                                                Класс активной ссылки
                                            </label>
                                            <label class="input">
                                                {{ Form::text('active_class', $element->active ?: 'active') }}
                                            </label>
                                            <label class="note margin-bottom-10">
                                                По умолчанию: active
                                            </label>
                                        </section>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </fieldset>

                <footer>
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
@stop

