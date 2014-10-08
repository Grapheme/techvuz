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

                    <section>
                        <label class="label">Максимальное кол-во уровней вложенности</label>
                        <label class="select">
                            {{ Form::select('nesting_level', array(5=>5, 4=>4, 3=>3, 2=>2, 1=>'1 (без вложенности)')) }}
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

