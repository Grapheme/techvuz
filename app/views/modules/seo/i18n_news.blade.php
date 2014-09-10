<header>Поисковая оптимизаци (SEO)</header>
<fieldset>
	<section>
		<label class="label">Адрес страницы (URL)</label>
		<label class="input">
			{{ Form::text('seo_url['.$locale.']', @$news_meta[$locale]->seo_url, array('pattern'=>"[a-z0-9\/\-_]{3,}")) }}
		</label>
		<div class="note">
			<strong>Адрес</strong> должен содержать латинские символы, цифры, символы подчеркивания и тире<br/>
			<strong>Если поле оставить пустым</strong>, адрес будет установлен как транслит названия новости
		</div>
	</section>
	<section>
		<label class="label">Title</label>
		<label class="input">
			{{ Form::text('seo_title['.$locale.']', @$news_meta[$locale]->seo_title) }}
		</label>
		<div class="note">
			<strong>Если поле оставить пустым</strong>, атрибут будет установлен как название новости
		</div>
	</section>
	<section>
		<label class="label">Description</label>
		<label class="textarea">
			{{ Form::textarea('seo_description['.$locale.']', @$news_meta[$locale]->seo_description, array('rows'=>2)) }}
		</label>
	</section>
	<section>
		<label class="label">Keywords</label>
		<label class="textarea">
			{{ Form::textarea('seo_keywords['.$locale.']', @$news_meta[$locale]->seo_keywords, array('rows'=>2)) }}
		</label>
	</section>
	<section>
		<label class="label">H1</label>
		<label class="input">
			{{ Form::text('seo_h1['.$locale.']', @$news_meta[$locale]->seo_h1) }}
		</label>
	</section>
</fieldset>