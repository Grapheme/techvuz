<?
#Helper::tad($element->metas->where('language', $locale_sign)->first());
#Helper::ta($element);
#Helper::dd($dic_settings);

## Search meta for current locale
$element_meta = new DicValMeta;
if (isset($element->metas) && is_object($element->metas) && $element->metas->count())
    foreach ($element->metas as $tmp) {
        #Helper::ta($tmp);
        if ($tmp->language == $locale_sign) {
            $element_meta = $tmp;
            break;
        }
    }
?>


{{-- If parent dictionary have the fields --}}
@if (count($fields_i18n))

    <?
    #Helper::ta($fields_i18n);

    ## Get element fields
    $element_fields = (isset($element->allfields) && is_array($element->allfields)
                       && isset($element->allfields[$locale_sign])
                       && is_array($element->allfields[$locale_sign])) ? $element->allfields[$locale_sign] : [];
    #Helper::ta($element_fields);

    ## Get element text fields
    $element_textfields = (isset($element->alltextfields) && is_array($element->alltextfields)
                       && isset($element->alltextfields[$locale_sign])
                       && is_array($element->alltextfields[$locale_sign])) ? $element->alltextfields[$locale_sign] : [];
    #Helper::tad($element_textfields);

    ## All element fields
    $element_fields_all = $element_fields + $element_textfields;
    #Helper::tad($element_fields_all);
    ?>

    @foreach ($fields_i18n as $field_name => $field)
        <?
        $field_meta_value = isset($element_fields_all[$field_name]) ? $element_fields_all[$field_name] : NULL;
        #Helper::ta($field_meta_value);

        $form_field = Helper::formField('fields_i18n[' . $locale_sign . '][' . $field_name . ']', $field, $field_meta_value, $element);
        #var_dump($form_field);

        if (!isset($form_field) || !$form_field)
            continue;

        ######################################################################
        ## Experimental, not tested! Conflicts are possible!
        ######################################################################
        if (isset($field['scripts'])) {
            #var_dump($field['scripts']);
            global $dicval_edit_scripts;
            #var_dump($dicval_edit_scripts);
            $dicval_edit_scripts[] = $field['scripts'];
            #var_dump($dicval_edit_scripts);
        }
        ######################################################################
        ?>

        <section>
            @if (!@$field['no_label'] && isset($field['title']))
                <label class="label">{{ $field['title'] }}</label>
            @endif
            @if (@$field['first_note'])
                <label class="note">{{ @$field['first_note'] }}</label>
            @endif
            <div class="input {{ $field['type'] }} {{ @$field['label_class'] }}">
                {{ $form_field }}
            </div>
            @if (@$field['second_note'])
                <label class="note">{{ @$field['second_note'] }}</label>
            @endif
        </section>

    @endforeach

@endif
