@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="how-it-works">
<!-- 
	<div class="dynamic-banners fotorama">

		<div class="dynamic-banner dynamic-banner-ot">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>


		<div class="dynamic-banner dynamic-banner-eb">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>

		<div class="dynamic-banner dynamic-banner-ptm">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>
		
		<div class="dynamic-banner dynamic-banner-noiz">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>

		<div class="dynamic-banner dynamic-banner-nop">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>

		<div class="dynamic-banner dynamic-banner-str">
			<div class="logo-holder">
			</div>
			<h3>Профессиональное образование и подготовка</h3>
			<p>Для СРО на портале Техвуз.рф</p>
		</div>

	</div>
-->
    @if(!empty($page->seo->h1))<h1>{{ $page->seo->h1 }}</h1>@endif
    <section class="htw">
    {{ $page->block('content') }}
    </section>
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop