@extends(Helper::layout())
@section('style') @stop
@section('content')
<main>
    <h1>{{ $page->block('top_h2') }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="directions">
        <h3>Направления</h3>
        <ul class="directions-ul clearfix">
        @foreach(Directions::whereActive(TRUE)->orderBy('order')->with('photo')->with('courses')->limit(6)->get() as $direction)
            <li class="directions-li">
            @if(!empty($direction->photo->name))
            {{ HTML::image(Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name,$direction->title,array('class'=>'directions-img')) }}
            @endif
                <a class="direction-link" href="{{ URL::route('page','catalog') }}#{{ BaseController::stringTranslite($direction->title) }}"></a>
                <div class="direction-name">
                    {{ $direction->title }}
                </div>
                <div class="courses-count">
                    {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
                </div>
            </li>
        @endforeach
        </ul>
    </section>
    <section class="benefits">
        {{ $page->block('benefits_title') }}
        {{ $page->block('benefits_list') }}
    </section>
    @include(Helper::layout('reviews-list'))
    @include(Helper::layout('news-list'))
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop