@extends(Helper::acclayout())


@section('style')
@stop


@section('content')

    @include($module['tpl'].'/menu')

    {{ Form::open(array('url' => URL::route('dic.import2', $dic_id), 'class' => 'smart-form', 'id' => $module['entity'].'-form2', 'role' => 'form', 'method' => 'POST')) }}

    <!-- Fields -->
	<div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">
                <header>Импорт данных: &laquo;{{ $dic->name }}&raquo; - шаг 1</header>
                <fieldset>

                    {{--
                    <section>
                        <label class="label">Файл с данными</label>
                        <label class="input">
                            {{ Form::file('import_file') }}
                        </label>
                        <label class="label">
                            <strong>ИЛИ</strong>
                        </label>
                    </section>
                    --}}

                    <section>
                        <label class="label">Данные для импорта</label>
                        <label class="textarea">
                            {{ Form::textarea('import_data') }}
                        </label>
                    </section>

                    <section>
                        <label class="label">Разделитель</label>
                        <label class="input">
                            {{ Form::text('delimeter', ';') }}
                        </label>
                    </section>

                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('trim', 1, true) }}
                            <i></i>
                            Сделать для каждой записи trim
                        </label>
                        <label class="input">
                            {{ Form::text('trim_params', '-') }}
                        </label>
                    </section>

                </fieldset>

                <footer>
                	<a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ URL::previous() }}">
                		<i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                	</a>
                	<button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                		<i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Отправить</span>
                	</button>
                </footer>

		    </div>
    	</section>

        <section class="col col-6">
            <div class="well">
                <header>Справка</header>
                <fieldset>

                    <p>
                        Для импорта данных выберите файл, или вставьте текст в поле для ввода.
                    </p>
                    <p>
                        Каждая строка будет являться одной записью в словаре. Если помимо названий строки содержат данные о других полях (свойствах записи), то они должны быть разделены - укажите разделитель. Например, в формате CSV в качестве разделителя чаще всего используется точка с запятой (;). Также может использоваться запятая (,), двоеточие (:) или вертикальная черта (|).
                    </p>

                </fieldset>
            </div>
        </section>

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