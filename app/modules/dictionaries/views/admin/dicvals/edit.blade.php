@extends(Helper::acclayout())


@section('style')
    {{ HTML::style('css/redactor.css') }}
@stop


@section('content')

    <?
    $create_title = "Редактировать запись:";
    $edit_title   = "Добавить запись:";

    $url        = 
        @$element->id
        ? action(is_numeric($dic_id) ? 'dicval.update' : 'entity.update', array('dic_id' => $dic_id, 'id' => $element->id))
        : action(is_numeric($dic_id) ? 'dicval.store'  : 'entity.store',  array('dic_id' => $dic_id));
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

                @if (!$dic->hide_slug || !$dic->hide_name)
                <fieldset>

                    @if (!$dic->hide_slug)
                    <section>
                        <label class="label">Системное имя (необязательно)</label>
                        <label class="input">
                            {{ Form::text('slug', null, array()) }}
                        </label>
                    </section>
                    @endif

                    @if (!$dic->hide_name)
                    <section>
                        <label class="label">Название</label>
                        <label class="input">
                            {{ Form::text('name', null, array()) }}
                        </label>
                    </section>
                    @endif

                </fieldset>
                @endif

                {{ Helper::dd_($fields['general']) }}

                @if (@count($fields['general']))
                <?
                $onsuccess_js = array();
                $element_fields = @is_object($element->fields) ? $element->fields->lists('value', 'key') : array();
                #Helper::d($element_fields);
                ?>
                <fieldset class="padding-top-10 clearfix">
                    @foreach ($fields['general'] as $field_name => $field)
                    <?
                    if (@$field['after_save_js'])
                        $onsuccess_js[] = $field['after_save_js'];
                    ?>
                    <section>
                        <label class="label">{{ @$field['title'] }}</label>
                        <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
                            {{ Helper::formField('fields[' . @$field_name . ']', @$field, @$element_fields[$field_name]) }}
                        </div>
                    </section>
                    @endforeach
                </fieldset>
                @endif

                @if (count($locales) > 1)
                <fieldset class="clearfix">
                    <section>
                        {{--
                        <label class="label">Индивидуальные настройки для разных языков (необязательно)</label>
                        --}}

                        <div class="widget-body">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <? $i = 0; ?>
                                @foreach ($locales as $locale_sign => $locale_name)
                                <li class="{{ !$i++ ? 'active' : '' }}">
                                    <a href="#locale_{{ $locale_sign }}" data-toggle="tab">
                                        {{ $locale_name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div id="myTabContent1" class="tab-content padding-10">
                                <? $i = 0; ?>
                                @foreach ($locales as $locale_sign => $locale_name)
                                <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }}" id="locale_{{ $locale_sign }}">

                                    @include($module['tpl'].'_dicval_meta', compact('locale_sign', 'locale_name', 'element'))

                                </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </fieldset>

                @else

                @foreach ($locales as $locale_sign => $locale_name)
                @include($module['tpl'].'_dicval_meta', compact('locale_sign', 'locale_name', 'element'))
                @endforeach

                @endif

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

        @if (count($locales) < 2 && Config::get('dic.seo.' . $dic->slug))
        <section class="col col-6">
            {{ ExtForm::seo('seo', $element->seo) }}
        </section>
        @endif
        <!-- /Form -->
   	</div>

    @if(@$element->id)
    @else
    {{ Form::hidden('redirect', action(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id))) }}
    @endif

    {{ Form::close() }}

@stop


@section('scripts')
    <script>
    var essence = '{{ $module['entity'] }}';
    var essence_name = '{{ $module['entity_name'] }}';
	var validation_rules = {
		name:              { required: true },
	};
	var validation_messages = {
		name:              { required: "Укажите название" },
	};
    </script>

    <script>
        var onsuccess_function = function() {

            // UPLOAD
            $('input[type=file].file_upload').each(function(){
                //console.log($(this).val());
                if ($(this).val() != '')
                    if (!$('input[type=hidden][name=redirect]').val())
                        location.href = location.href;
            });

            // VIDEO
            $('input[type=file].video_image_upload').each(function(){
                //console.log($(this).val());
                if ($(this).val() != '')
                    if (!$('input[type=hidden][name=redirect]').val())
                        location.href = location.href;
            });

            @if (@count($onsuccess_js))
                {{ implode("\n", @$onsuccess_js) }}
            @endif
        }
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

    {{ HTML::script('js/vendor/redactor.min.js') }}
    {{ HTML::script('js/system/redactor-config.js') }}

    {{ HTML::script('js/modules/gallery.js') }}

@stop