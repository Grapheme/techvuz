@extends(Helper::layout())
@section('style') @stop
@section('content')
<main>
    <h1>{{ $page->block('top_h2') }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <section class="directions">
        <h3><a href="{{ pageurl('catalog') }}">Направления</a></h3>
        <ul class="directions-ul clearfix">
        <?php $totalCourses = 0; ?>
        <?php $totalDirections = 0; ?>
        @foreach(Directions::whereActive(TRUE)->orderBy('order')->with('photo')->with('courses')->get() as $key => $direction)
            <li
                @if($direction->in_progress) 
                    class="directions-li direction-in-progress"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Направление находится в разработке"
                @else
                    class="directions-li"
                @endif
            >
            @if(!empty($direction->photo->name))
            {{ HTML::image(Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name,$direction->title,array('class'=>'directions-img')) }}
            @endif
                <a class="direction-link" href="{{ pageurl('catalog') }}#ui-id-{{ $key+1 }}{{-- 2 * $key + 1 --}}"></a>
                <div class="direction-name">
                    {{ $direction->title }}
                </div>
                <div class="courses-count">
                    {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
                </div>
                <?php $totalDirections++; ?>
                <?php $totalCourses += $direction->courses->count(); ?>
            </li>
        @endforeach
        </ul>
        <div class="sum-block">
            <div class="count-add">
                <div class="container-fluid">
                    <div class="row no-gutter margin-top-20">
                        <div style="float: right; margin-right: -15px;" class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 counter-string">
                            <a href="{{ pageurl('catalog') }}"><div class="count-add-sign">Всего направлений</div>
                            <div class="count-add-num">{{ $totalDirections+10 }}</div>
                            <div class="count-add-dots"></div></a>
                        </div>
                        <div style="float: left; margin-left: -15px; margin-top: -17px;" class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 counter-string">
                            <a href="{{ pageurl('catalog') }}"><div class="count-add-sign">Всего курсов</div>
                            <div class="count-add-num">{{ $totalCourses }}</div>
                            <div class="count-add-dots"></div></a>
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
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@if(Auth::guest() && Input::has('login'))
<script>
    $(function(){
        $(".js-login").click();
    });
</script>
@endif
@stop