@extends(Helper::layout())
@section('style') @stop
@section('content')
<main>
    <h1>{{ $page->block('top_h2') }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="directions">
        <h3><a href="{{ URL::route('page','catalog') }}">Направления</a></h3>
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
        <div class="sum-block">
            <div class="count-add">
                <div class="container-fluid">
                    <div class="row no-gutter margin-top-20">
                        <div class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="count-add-sign">Всего курсов</div>
                            <div class="count-add-num">17</div>
                            <div class="count-add-dots"></div>
                        </div>
                    </div>                                
                </div>
            </div>
        </div>
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