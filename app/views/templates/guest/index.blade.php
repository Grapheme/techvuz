@extends(Helper::layout())
@section('style') @stop
@section('content')
<main>
    {{ $page->block('top_h2') }}
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="directions">
        <h3>Направления</h3>
        <ul class="directions-ul clearfix">
        @foreach(Directions::with('photo')->with('courses')->get() as $direction)
            <li class="directions-li">
            @if(!empty($direction->photo->name))
            {{ HTML::image(Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name,$direction->title) }}
            @endif
                <a class="direction-link" href="#"></a>
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
</main>
@include(Helper::layout('reviews-list'))
@include(Helper::layout('news-list'))
@stop
@section('overlays')
@stop
@section('scripts')
@stop