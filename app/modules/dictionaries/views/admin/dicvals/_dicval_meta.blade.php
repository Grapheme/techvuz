
<?
#Helper::tad($element->metas->where('language', $locale_sign)->first());
#Helper::ta($element);
#Helper::dd($dic_settings);

if (@is_callable($dic_settings['fields_i18n']))
    $fields_i18n = $dic_settings['fields_i18n']();

$element_meta = new DicValMeta;
if (@is_object($element->metas) && $element->metas->count())
    foreach ($element->metas as $tmp) {
        #Helper::ta($tmp);
        if ($tmp->language == $locale_sign) {
            $element_meta = $tmp;
            break;
        }
    }
?>

@if (count($locales) > 1)
    <section>
        <label class="label">Название</label>
        <label class="input select input-select2">
            {{ Form::text('locales[' . $locale_sign . '][name]', $element_meta->name, array()) }}
        </label>
    </section>
@endif

@if (count($locales) > 1)

    @if (@count($fields_i18n))
<?
        $element_fields = array();
        if (@is_object($element->allfields)) {
            $element_fields = $element->allfields;
            foreach ($element_fields as $f => $field) {
                if (!$field->language)
                    unset($element_fields[$f]);
            }
            #$element_fields = $element_fields->lists('value', 'key');
            #Helper::ta($element_fields);
        }
?>

        @foreach ($fields_i18n as $field_name => $field)
<?
            $field_meta = new DicFieldVal();
            foreach ($element_fields as $tmp) {
                #Helper::ta($tmp);
                if ($tmp->key == @$field_name && $tmp->language == $locale_sign) {
                    $field_meta = $tmp;
                    #Helper::ta($field_meta);
                    break;
                }
            }
            $form_field = Helper::formField('fields_i18n[' . $locale_sign . '][' . $field_name . ']', $field, @$field_meta->value, $element);
            if (!$form_field)
                continue;

            #$form_field = false;
?>

            <section>
                <label class="label">{{ $field['title'] }}</label>
                <label class="input {{ $field['type'] }}">
                    {{ $form_field }}
                </label>
            </section>

        @endforeach

    @endif

@endif
