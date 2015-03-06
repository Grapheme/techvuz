@extends(Helper::layout())
@section('style')
    <link href='css/fotorama.css' rel='stylesheet' type='text/css'>
@stop
@section('content')
<main class="catalog">
    @if(!empty($page->seo->h1))<h1>{{ $page->seo->h1 }}</h1>@endif
    <div class="print-link">
        <a href="#" onclick="window.print();return false;">Распечатать каталог</a> <span class="icon icon-print"></span>
    </div>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <div class="js-fotorama banners-cont">
    <?php $banner_id = 1; ?>
    @foreach(Dictionary::valuesBySlug('information-baners') as $baner)
        <?php $fields = modifyKeys($baner->fields,'key'); ?>
        @if(isset($fields['active']) && $fields['active']['value'] == 1 )
        <div class="fotorama-item">
            <div class="banner banner--red banner-{{ $banner_id }}">
                <span>{{ $fields['content']['value'] }}</span>
            </div>
        </div>
        <?php $banner_id++; ?>
        @endif
    @endforeach
    </div>
    <div class="accordion">
    <?php
        $directions = Directions::whereActive(TRUE)->orderBy('order')->with('photo')
            ->with(array('courses'=>function($query){
                $query->whereActive(TRUE);
                $query->with('seo');
            }))->get();
    ?>
    @foreach($directions as $direction)
        @if($direction->in_progress)
            <!-- это условие говорит о том что направление находится в разработке! -->
        @endif
        <div
            @if($direction->in_progress)
                class="accordion-header direction-in-progress"
                data-toggle="tooltip"
                data-placement="top"
                title="Направление находится в разработке"
            @else
                class="accordion-header"
            @endif
        >
        @if(!empty($direction->photo->name))
            <div class="accordion-img" style="background-image: url('{{ Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name }}');"></div>
        @endif
            <h3>{{ $direction->title }}<a href="#" class="js-print-part print-link" data-id="print-{{ $direction->id }}"><span class="icon icon-print"></span></a></h3>
            <div class="acc-courses">
                {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
            </div>
        </div>
        <div class="accordion-body" id="print-{{ $direction->id }}">
        @if($direction->courses->count())
            <table>
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Часы</th>
                    <th>Цена</th>
                </tr>
            <?php
                $accountDiscount = getAccountDiscount();
                $globalDiscount = getGlobalDiscount();
            ?>
            @foreach($direction->courses as $course)
                @if($course->in_progress)
                    <!-- это условие говорит о том что курс находится в разработке! -->
                @endif
                <tr @if($course->in_progress)
                        data-toggle="tooltip"
                        data-placement="left"
                        title="Курс находится в разработке"
                        class="course-in-progress" 
                    @endif
                >
                    <td>
                    @if(!empty($course->seo))
                        <a href="{{ URL::route('course-page',$course->seo->url) }}">{{ $course->title }}</a>
                    @else
                        {{ $course->title }}
                    @endif
                    </td>
                    <td><span class="code">{{ $course->code }}</span></td>
                    <td><span class="code">{{ $course->hours }}</span></td>
                    <td>
                    <?php $discountPrice = FALSE; ?>
                    @if($direction->use_discount && $course->use_discount)
                        <?php $discountPrice = calculateDiscount(array($direction->discount,$course->discount,$accountDiscount,$globalDiscount),$course->price,FALSE); ?>
                    @endif
                    @if($discountPrice === FALSE || $discountPrice == $course->price)
                        <span class="price">{{ number_format($course->price,0,'.',' ')  }}.–</span>
                    @else
                        <span class="price"><s>{{ number_format($course->price,0,'.',' ')  }}.–</s></span>
                        <br><span class="price">{{ number_format($discountPrice,0,'.',' ')  }}.–</span>
                    @endif
                    </td>
                </tr>
            @endforeach
            </table>        
        @endif
        </div>
    @endforeach
    </div>
    <div class="desc">{{ $page->block('seo') }}</div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
    <script src="{{URL::to('js/vendor/fotorama.js')}}"></script>
    <script>
        var banner_fotorama = function() {
            var mheight = 0;
            var $fotoramaDiv;
            var fotorama;
            var setHeight = function() {
                $('.banner').each(function(){
                    var this_height = $(this).height() +6;
                    if(this_height > mheight) {
                        mheight = this_height;
                    }
                });
            }
            setTimeout(function(){
                setHeight();
                $fotoramaDiv = $('.js-fotorama').fotorama({
                    width: '100%',
                    height: mheight,
                    arrows: false,
                    nav: false,
                    click: false,
                    swipe: false,
                    autoplay: 5000
                });
                fotorama = $fotoramaDiv.data('fotorama');
                $(window).on('resize', function(){
                    setHeight();
                    fotorama.resize({
                        height: mheight
                    });
                });
            }, 500);
        }
        $(function(){
            banner_fotorama();
        });
    </script>
@stop