<?
#Helper::dd($params);
$seo = isset($value) && is_object($value) ? $value : new Seo;
?>
<div class="well">

    <header>SEO</header>

    <fieldset>

        <section>
            <label class="label">Title</label>
            <label class="input">
                {{ Form::text($name.'[title]', $seo->title) }}
            </label>
        </section>

        <section>
            <label class="label">Description</label>
            <label class="textarea">
                {{ Form::textarea($name.'[description]', $seo->description, array('rows' => 4)) }}
            </label>
        </section>

        <section>
            <label class="label">Keywords</label>
            <label class="textarea">
                {{ Form::textarea($name.'[keywords]', $seo->keywords, array('rows' => 3)) }}
            </label>
        </section>

        <section>
            <label class="label">URL</label>
            <label class="input">
                {{ Form::text($name.'[url]', $seo->url) }}
            </label>
        </section>

        <section>
            <label class="label">H1</label>
            <label class="input">
                {{ Form::text($name.'[h1]', $seo->h1) }}
            </label>
        </section>

    </fieldset>

</div>

<style>
    .redactor_redactor_preview {
        height: 80px !important;
    }
    .redactor_redactor_content {
        height: 200px !important;
    }
</style>