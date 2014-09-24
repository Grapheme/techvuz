
<?
#Helper::tad($element->metas->where('language', $locale_sign)->first());
#Helper::ta($element);
$element_meta = new NewsMeta;
foreach ($element->metas as $tmp) {
    #Helper::ta($tmp);
    if ($tmp->language == $locale_sign) {
        $element_meta = $tmp;
        break;
    }
}
?>
<fieldset class="col col-lg-7 clearfix">

    <section>
        <label class="label">Название</label>
        <label class="input">
            {{ Form::text('locales['.$locale_sign.'][title]', $element_meta->title) }}
        </label>
    </section>

    <section>
        <label class="label">Анонс</label>
        <label class="textarea">
            {{ Form::textarea('locales['.$locale_sign.'][preview]', $element_meta->preview, array('class' => 'redactor redactor_preview')) }}
        </label>
    </section>

    <section>
        <label class="label">Содержание</label>
        <label class="textarea">
            {{ Form::textarea('locales['.$locale_sign.'][content]', $element_meta->content, array('class' => 'redactor redactor_content')) }}
        </label>
    </section>

    @if (Allow::module('galleries'))
    <section>
        <label class="label">Основная фотография</label>
        <label class="input">
            {{ ExtForm::image('locales[' . $locale_sign . '][photo_id]', $element_meta->photo_id) }}
        </label>
    </section>

    <section>
        <label class="label">Галерея</label>
        <label class="input">
            {{ ExtForm::gallery('locales[' . $locale_sign . '][gallery_id]', $element_meta->gallery_id) }}
        </label>
    </section>
    @endif

</fieldset>

<span><!-- .smart-form fieldset + fieldset border-top 1px fix --></span>

<fieldset class="col col-lg-5 clearfix margin-bottom-10">

    {{ ExtForm::seo('seo['.$locale_sign.']', $element_meta->seo) }}

</fieldset>