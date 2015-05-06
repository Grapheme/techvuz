@extends(Helper::acclayout())


@section('style')
@stop


@section('content')

    @include($module['tpl'].'/menu')

    {{ Form::open(array('url' => URL::route('dic.import3', $dic_id), 'class' => 'smart-form', 'id' => $module['entity'].'-form2', 'role' => 'form', 'method' => 'POST')) }}

    <!-- Fields -->
	<div class="row">

        <!-- Form -->
        <section class="col col-12">
            <div class="well">
                <header>Импорт данных: &laquo;{{ $dic->name }}&raquo; - шаг 2</header>

                <fieldset>

                    <section>
                        <label class="label">
                            Что делать, если запись с таким Названием или Системным именем уже существуют?
                        </label>
                        <label class="select">
                            {{ Form::select('rewrite_mode', array('1' => 'Оставить Название, обновить Системное имя', '2' => 'Оставить Системное имя, обновить Название')) }}
                        </label>
                    </section>

                </fieldset>

                <fieldset>

                    <section>
                        <label class="checkbox">
                            {{ Form::checkbox('set_slug', 1) }}
                            <i></i>
                            Установить записям Системное имя - транслит от Названия
                        </label>
                        <label class="select">
                            {{ Form::select('set_slug_elements', array('all' => 'Всем', 'empty' => 'Только с пустым системным именем')) }}
                        </label>
                        <label class="checkbox">
                            {{ Form::checkbox('set_ucfirst', 1) }}
                            <i></i>
                            Названия записей - с большой буквы
                        </label>
                    </section>

                </fieldset>

                <fieldset>

                    <label class="label">
                        Сопоставьте колонки в записях с полями словаря:
                    </label>

                    <table class="table table-bordered margin-bottom-10">
                        <thead>
                            <tr>
                                @for ($i = 0; $i < $max; $i++)
                                <td>
                                    <label class="select">
                                        {{ Form::select('fields[]', $fields, $max == 1 ? 'name' : NULL) }}
                                    </label>
                                </td>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($array as $arr)
                            <tr>
                                @for ($i = 0; $i < $max; $i++)
                                <?
                                $val = @trim($arr[$i]);
                                ?>
                                <td>
                                    <label class="textarea">
                                        {{ Form::textarea('values['.$i.'][]', $val, array('rows' => 2)) }}
                                    </label>
                                </td>
                                @endfor
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

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

        {{--
        <section class="col col-3">
            <div class="well">
                <header>Справка</header>
                <fieldset>

                    <p>
                        Сопоставьте колонки в записях с полями словаря.
                    </p>

                </fieldset>
            </div>
        </section>
        --}}

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