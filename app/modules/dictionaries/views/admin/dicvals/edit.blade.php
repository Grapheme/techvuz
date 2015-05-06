@extends(Helper::acclayout())


@section('style')
    @if (@trim($dic_settings['style']))
        <style>
            {{ $dic_settings['style'] }}
        </style>
    @endif
@stop


@section('content')

    <?
    $create_title = "Редактировать запись:";
    $edit_title = "Добавить запись:";

    $url = (isset($element->id) && $element->id) ? action(is_numeric($dic_id) ? 'dicval.update' : 'entity.update', array('dic_id' => $dic_id, 'id' => $element->id)) : action(is_numeric($dic_id) ? 'dicval.store' : 'entity.store', array('dic_id' => $dic_id));
    $method = @$element->id ? 'PUT' : 'POST';
    $form_title = @$element->id ? $create_title : $edit_title;
    ?>

    @include($module['tpl'].'/menu')

    {{ Helper::ta_($element) }}

    {{ Form::model($element, array('url' => $url, 'class' => 'smart-form', 'id' => $module['entity'].'-form', 'role' => 'form', 'method' => $method, 'files' => TRUE)) }}

    @if (
        is_object($element->original_version)
        && $element->original_version->id
        #&& $element->original_version->updated_at >= $element->updated_at
    )
        <?
        $original_versions_count = count($element->original_version->versions);
        $newer_versions_count = 0;
        foreach ($element->original_version->versions as $version)
            if ($version->id != $element->id && $version->updated_at >= $element->updated_at)
                ++$newer_versions_count;
        ?>
        <p class="alert alert-warning fade in padding-10 margin-bottom-10">
            <i class="fa-fw fa fa-warning"></i> <strong>Внимание!</strong> Вы просматриваете резервную копию оригинальной записи.<br /> Вы можете
            <a href="{{ action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $element->original_version->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}">перейти к оригиналу</a>
            или
            <a href="#" class="restore_version" data-url="{{ action(is_numeric($dic_id) ? 'dicval.restore' : 'entity.restore', array('dic_id' => $dic_id, 'id' => $element->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}">восстановить эту копию</a>
            в качестве оригинала.<br />
            @if ($original_versions_count > 1)
                Также к просмотру доступно еще
                <a href="#versions">{{ trans_choice(':count резервная копия|:count резервных копии|:count резервных копий', $original_versions_count-1, array(), 'ru') }}</a>@if($newer_versions_count), в том числе
                {{ trans_choice(':count более свежая|:count более свежие|:count более свежих', $newer_versions_count, array(), 'ru') }}@endif.
            @endif
        </p>
    @endif

    <?
    global $dicval_edit_scripts;
    $dicval_edit_scripts = array();
    ?>

    <!-- Fields -->
    <div class="row">

        <!-- Form -->
        <section class="col col-6">
            <div class="well">
                <header>{{ $form_title }}</header>

                <fieldset>

                    @if (!$dic->hide_slug)
                        <section>
                            <label class="label">{{ isset($dic_settings['slug_label']) ? $dic_settings['slug_label'] : 'Системное имя (необязательно)' }}</label> <label class="input">
                                {{ Form::text('slug', NULL, array()) }}
                            </label> <label class="note second_note">
                                @if (isset($dic_settings['slug_note']))
                                    {{ $dic_settings['slug_note'] }}
                                @else
                                    Только символы англ. алфавита, знаки _ и -, цифры
                                @endif
                            </label>
                        </section>
                    @endif

                    @if (!@$dic_settings['hide_name'])
                        <section>
                            <label class="label">{{ $dic->name_title ?: 'Название' }}</label> <label class="input">
                                {{ Form::text('name', NULL, array('required' => 'required')) }}
                                @if (isset($dic_settings['name_note']))
                                    {{ $dic_settings['name_note'] }}
                                @endif
                            </label>
                        </section>
                    @endif

                </fieldset>

                {{ Helper::dd_($dic_settings) }}

                @if (@is_callable($dic_settings['fields']) && NULL !== ($fields_general = $dic_settings['fields']($element)) && count($fields_general))
                    <?

                    #dd($fields_general);
                    #Helper::ta($element);
                    $onsuccess_js = array();
                    ?>
                    <fieldset class="padding-top-10 clearfix">
                        @foreach ($fields_general as $field_name => $field)
                            <?
                            $field['_name'] = $field_name;
                            if (isset($field['after_save_js']))
                                $onsuccess_js[] = $field['after_save_js'];
                            if (isset($field['scripts']))
                                $dicval_edit_scripts[] = $field['scripts'];

                            $value = isset($element->allfields) && isset($element->allfields[Config::get('app.locale')]) && isset($element->allfields[Config::get('app.locale')][$field_name]) ? $element->allfields[Config::get('app.locale')][$field_name] : NULL;
                            #Helper::ta($value);
                            ?>
                            <section>
                                @if (!@$field['no_label'] && isset($field['title']))
                                    <label class="label">{{ @$field['title'] }}&nbsp;</label>
                                @endif
                                @if (@$field['first_note'])
                                    <label class="note first_note">{{ @$field['first_note'] }}</label>
                                @endif
                                <div class="input {{ @$field['type'] }} {{ @$field['label_class'] }}">
                                    {{ Helper::formField('fields[' . @$field_name . ']', @$field, $value, $element) }}
                                </div>
                                @if (@$field['second_note'])
                                    <label class="note second_note">{{ @$field['second_note'] }}</label>
                                @endif
                            </section>
                        @endforeach
                    </fieldset>
                @endif

                {{-- @if (count($locales) > 1) --}}
                <?
                $fields_i18n = array();
                if (isset($dic_settings['fields_i18n']) && is_callable($dic_settings['fields_i18n']))
                    $fields_i18n = $dic_settings['fields_i18n']($element);
                ?>
                @if (count($fields_i18n))
                    <?
                    #Helper::ta($fields_i18n);
                    ?>
                    <fieldset class="clearfix">
                        <section>
                            {{--
                            <label class="label">Индивидуальные настройки для разных языков (необязательно)</label>
                            --}}

                            <div class="widget-body">
                                @if (count($locales) > 1)
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
                                @endif
                                <div id="myTabContent1" class="tab-content{{ count($locales) > 1 ? ' padding-10' : '' }}">
                                    <? $i = 0; ?>
                                    @foreach ($locales as $locale_sign => $locale_name)
                                        <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }}" id="locale_{{ $locale_sign }}">

                                            @include($module['tpl'].'_dicval_meta', compact('locale_sign', 'locale_name', 'element', 'fields_i18n'))

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
                    @if ($element->version_of == NULL)
                        <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                            <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                        </a>
                        <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                        </button>
                    @else
                        <label class="note margin-top-0"> Нельзя сохранять изменения в резервной копии </label>
                    @endif
                </footer>

            </div>
        </section>


        @if (Allow::action('seo', 'edit') && NULL != ($dic_seo_params = Config::get('dic/' . $dic->slug . '.seo')))
            <section class="col col-6">
                <div class="well">
                    <header>Поисковая оптимизация</header>
                    <fieldset class="padding-bottom-15">
                        <div class="widget-body">
                            @if (count($locales) > 1)
                                <ul id="myTab2" class="nav nav-tabs bordered">
                                    <? $i = 0; ?>
                                    @foreach ($locales as $locale_sign => $locale_name)
                                        <li class="{{ !$i++ ? 'active' : '' }}">
                                            <a href="#seo_locale_{{ $locale_sign }}" data-toggle="tab">
                                                {{ $locale_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            {{ Helper::ta_($element) }}

                            <div id="myTabContent2" class="tab-content @if(count($locales) > 1) padding-10 @endif">
                                <? $i = 0; ?>
                                @foreach ($locales as $locale_sign => $locale_name)
                                    <div class="tab-pane fade {{ !$i++ ? 'active in' : '' }} clearfix" id="seo_locale_{{ $locale_sign }}">

                                        {{ ExtForm::seo('seo[' . $locale_sign . ']', @$element->seos[$locale_sign], $dic_seo_params) }}

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </fieldset>
                    <footer>
                        @if ($element->version_of == NULL)
                            <a class="btn btn-default no-margin regular-10 uppercase pull-left btn-spinner" href="{{ link::previous() }}">
                                <i class="fa fa-arrow-left hidden"></i> <span class="btn-response-text">Назад</span>
                            </a>
                            <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Сохранить</span>
                            </button>
                        @else
                            <label class="note margin-top-0"> Нельзя сохранять изменения в резервной копии </label>
                        @endif
                    </footer>
                </div>
            </section>
        @endif


        @if (
            Config::get('dic/' . $dic->slug . '.versions') && Allow::action($module['group'], 'dicval_restore') && $element->id
        )
            <section class="col col-6">
                <div class="well">

                    <a name="versions"></a>
                    <header>Резервные копии</header>
                    <fieldset class="padding-bottom-15">

                        @if (
                            (isset($element->versions) && count($element->versions))
                            || (isset($element->original_version->versions) && count($element->original_version->versions))
                        )
                            <?
                            $can_restore = true;
                            $dicval_versions = count($element->versions) ? $element->versions : $element->original_version->versions;
                            $show_original = count($element->versions) ? false : true;
                            ?>
                            <ul class="margin-left-15">
                                @if ($show_original)
                                    <li>
                                        <a href="{{ action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $element->original_version->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}">{{ $element->original_version->name }} - {{ $element->original_version->updated_at->format('H:i:s, d.m.Y') }}</a>
                                        [оригинал]
                                    </li>
                                @endif
                                @foreach ($dicval_versions as $v => $version)
                                    <li>
                                        @if ($version->id != $element->id)
                                            <a href="{{ action(is_numeric($dic_id) ? 'dicval.edit' : 'entity.edit', array('dic_id' => $dic_id, 'id' => $version->id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '') }}">{{ $version->name }} - {{ $version->updated_at->format('H:i:s, d.m.Y') }}</a>
                                        @else
                                            {{ $version->name }} - {{ $version->updated_at->format('H:i:s, d.m.Y') }} [текущая]
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <?
                            $can_restore = false;
                            ?>
                            <p>На данный момент у записи нет ни одной резервной копии</p>
                        @endif

                    </fieldset>
                    <footer>
                        @if ($can_restore)
                            <label class="note margin-top-0"> Вы можете восстановить состояние текущей записи из резервной копии </label>
                        @endif
                    </footer>

                </div>
            </section>
            @endif

                    <!-- /Form -->
    </div>

    @if(@$element->id)
    @else
        {{ Form::hidden('redirect', action(is_numeric($dic_id) ? 'dicval.index' : 'entity.index', array('dic_id' => $dic_id)) . (Request::getQueryString() ? '?' . Request::getQueryString() : '')) }}
    @endif

    {{ Form::close() }}

@stop


@section('scripts')
    <script>
        var essence = '{{ $module['entity'] }}';
        var essence_name = '{{ $module['entity_name'] }}';
        @if (isset($dic_settings['custom_validation']) && trim($dic_settings['custom_validation']) != '')
        {{ $dic_settings['custom_validation'] }}
                @else
                    var validation_rules = {
            'name': {required: true}
        };
        var validation_messages = {
            'name': {required: "Укажите название"}
        };
        @endif
    </script>

    <script>
        @if (@$dic_settings['unique_slug'])
        var CheckDicvalSlugUnique = true;
                @else
                var CheckDicvalSlugUnique = false;
                @endif

                var onsuccess_function = function () {

            // UPLOAD
            $('input[type=file].file_upload').each(function () {
                //console.log($(this).val());
                if ($(this).val() != '')
                    if (!$('input[type=hidden][name=redirect]').val())
                        location.href = location.href;
            });

            // VIDEO
            $('input[type=file].video_image_upload').each(function () {
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

    <script type="text/javascript">
        if (typeof pageSetUp === 'function') {
            pageSetUp();
        }
        if (typeof runDicValFormValidation === 'function') {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}", runDicValFormValidation);
        } else {
            loadScript("{{ asset('private/js/vendor/jquery-form.min.js'); }}");
        }
    </script>

    {{ HTML::script('private/js/vendor/redactor.min.js') }}
    {{ HTML::script('private/js/system/redactor-config.js') }}

    {{-- HTML::script('private/js/modules/gallery.js') --}}
    {{ HTML::script('private/js/plugin/select2/select2.min.js') }}

    @if (@trim($dic_settings['javascript']))
        <script>
            {{ $dic_settings['javascript'] }}
        </script>
    @endif

    @if (isset($dicval_edit_scripts) && is_array($dicval_edit_scripts) && count($dicval_edit_scripts))
        {{ implode("\n", $dicval_edit_scripts) }}
    @endif

@stop