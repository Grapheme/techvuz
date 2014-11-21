@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="catalog">
    <div class="print-link">
        <a href="#">Распечатать каталог</a> <span class="icon icon-print"></span>
    </div>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
@foreach(Dictionary::valuesBySlug('information-baners') as $baner)
    <?php $fields = modifyKeys($baner->fields,'key');?>
    @if(isset($fields['active']) && $fields['active']['value'] == 1 )
    <div class="banner banner--red">
        <span>{{ $fields['content']['value'] }}</span>
    </div>
    @endif
@endforeach
    <div class="accordion">
    <?php
        $directions = Directions::whereActive(TRUE)->orderBy('order')->with('photo')
            ->with(array('courses'=>function($query){
                $query->whereActive(TRUE);
                $query->with('seo');
            }))->get();
    ?>
    @foreach($directions as $direction)
        <a name="{{ BaseController::stringTranslite($direction->title) }}"></a>
        <div class="accordion-header">
        @if(!empty($direction->photo->name))
            <div class="accordion-img" style="background-image: url('{{ Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name }}');"></div>
        @endif
            <h3>{{ $direction->title }}</h3>
            <div class="acc-courses">
                {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
            </div>
        </div>
        @if($direction->courses->count())
        <div class="accordion-body">
            <table>
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Часы</th>
                    <th>Цена</th>
                </tr>
            <?php $accountDiscount = getAccountDiscount();?>
            @foreach($direction->courses as $course)
                <tr>
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
                    <?php
                        $discountPrice = calculateDiscount(array($direction->discount,$course->discount,$accountDiscount),$course->price);
                    ?>
                    @if($discountPrice === FALSE)
                        <span class="price">{{ number_format($course->price,0,'.',' ')  }}.–</span>
                    @else
                        <span class="price"><s>{{ number_format($course->price,0,'.',' ')  }}.–</s></span>
                        <br><span class="price">{{ number_format($discountPrice,0,'.',' ')  }}.–</span>
                    @endif
                    </td>
                </tr>
            @endforeach
            </table>
        </div>
        @endif
    @endforeach
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop