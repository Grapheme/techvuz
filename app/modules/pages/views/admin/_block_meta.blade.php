<?
if ( @count($element->metas) && isset($element->metas[$locale_sign]) )
    $block_meta = $element->metas[$locale_sign];
else
    $block_meta = false;
?>

    <label class="control-label margin-top-10">
        Содержимое блока
    </label>
    {{ Form::textarea('locales[' . $locale_sign . '][content]', ($block_meta ? $block_meta->content : false), array('class' => 'form-control redactor redactor-no-filter redactor_450', 'placeholder' => 'Содержимое блока') ) }}

    @if (count($locales) > 1)

        <label class="control-label margin-top-10">
            <small>Шаблон языковой версии блока (необязательно)</small>
        </label>
        {{ Form::select('locales[' . $locale_sign . '][template]', array('По умолчанию')+$templates, null, array('class' => 'form-control')) }}

    @endif
