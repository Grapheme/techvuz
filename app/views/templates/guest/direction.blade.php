@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="catalog">
@if(!empty($page->seo->h1))<h1>{{ $page->seo->h1 }}</h1>@endif
    <div class="desc">
        {{ $page->block('seo') }}
    </div>
    <?php
        $path = Request::path();
        $segments = explode('-',$path);
        $directionCodes = array();
        foreach(Directions::whereActive(TRUE)->lists('code') as $code):
            $directionCodes[BaseController::stringTranslite($code)] = $code ;
        endforeach;
        if (isset($segments[0]) && isset($directionCodes[$segments[0]])):
            $direction = Directions::whereActive(TRUE)->where('code',$directionCodes[$segments[0]])->with('photo')
                    ->with(array('courses'=>function($query){
                        $query->whereActive(TRUE);
                        $query->with('seo');
                    }))->first();
        else:
            $direction = FALSE;
        endif;
    ?>
@if($direction)
    <div class="accordion none-js">
        <div
        @if($direction->in_progress)
            class="accordion-header {{ BaseController::stringTranslite($direction->code) }}-head-color direction-in-progress"
            data-toggle="tooltip"
            data-placement="top"
            title="Направление находится в разработке"
        @else
            class="accordion-header {{ BaseController::stringTranslite($direction->code) }}-head-color"
        @endif
        >
            @if(!empty($direction->photo->name))
                <div class="accordion-img" style="background-image: url('{{ Config::get('site.galleries_photo_public_dir').'/'.$direction->photo->name }}');"></div>
            @endif
            <h3>{{ $direction->title }}</h3>
            <div class="acc-courses">
                {{ $direction->courses->count() }} {{ Lang::choice('курс|курса|курсов',$direction->courses->count()); }}
            </div>
        </div>
        <div class="accordion-body">
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
    </div>
@else
    <p>Неверно указан идентификатор страницы</p>
@endif
    <div class="desc margin-top-20">
        @if (count($page->blocks))
            @foreach ($page->blocks as $block)
                @if($block->slug != 'seo')
                    {{ $page->block($block->slug) }}
                @endif
            @endforeach
        @endif
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop