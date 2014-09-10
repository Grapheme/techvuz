
<?
#Helper::tad($element->metas->where('language', $locale_sign)->first());
#Helper::ta($element);
$element_meta = new PageMeta;
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
            <label class="label">Шаблон</label>
            <label class="input select input-select2">
                {{ Form::select('locales[' . $locale_sign . '][template]', array('По умолчанию')+$templates, $element_meta->template) }}
            </label>
        </section>

        <span></span>

    @endif

    <div class="clearfix margin-bottom-10">
        {{ ExtForm::seo('locales['.$locale_sign.'][seo]', $element_meta->seo) }}
    </div>