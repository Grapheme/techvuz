<header>Поисковая оптимизаци (SEO)</header>
<fieldset>
	<section>
		@if(I18nPage::count() && !@$page->start_page)
			<label class="label">Адрес страницы (URL)</label>
			<label class="input">
				{{ Form::text('seo_url['.$locale.']', @$page_meta[$locale]->seo_url, array('pattern'=>"[a-z0-9\/\-_]{3,}")) }}
			</label>
			<div class="note">
				<strong>Адрес</strong> должен содержать латинские символы, цифры, символы подчеркивания и тире<br/>
				<strong>Если поле оставить пустым</strong>, адрес будет установлен как транслит названия страницы
			</div>
		@else
		<div class="alert alert-info margin-top-5 padding-5">
			<i class="fa-fw fa fa-info"></i>
			<strong>Внимание!</strong> Это главная страница. Изменить URL нельзя.
		</div>
		@endif
	</section>
	<section>
		<label class="label">Title</label>
		<label class="input">
			{{ Form::text('seo_title['.$locale.']', @$page_meta[$locale]->seo_title) }}
		</label>
		<div class="note">
			<strong>Если поле оставить пустым</strong>, атрибут будет установлен как название страницы
		</div>
	</section>
	<section>
		<label class="label">Description</label>
		<label class="textarea">
			{{ Form::textarea('seo_description['.$locale.']', @$page_meta[$locale]->seo_description, array('rows'=>2)) }}
		</label>
	</section>
	<section>
		<label class="label">Keywords</label>
		<label class="textarea">
			{{ Form::textarea('seo_keywords['.$locale.']', @$page_meta[$locale]->seo_keywords, array('rows'=>2)) }}
		</label>
	</section>
	<section>
		<label class="label">H1</label>
		<label class="input">
			{{ Form::text('seo_h1['.$locale.']', @$page_meta[$locale]->seo_h1) }}
		</label>
	</section>
</fieldset>