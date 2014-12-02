@extends(Helper::layout())
@section('style')
@stop
@section('content')
<main class="catalog">
    <h1>Покупка курсов, шаг 1</h1>
    @if(Session::get('message'))
    <div>
        <p>{{ Session::get('message') }}</p>
    </div>
    @endif
    {{ Form::open(array('route'=>'ordering-courses-store','class'=>'authenticated accordion-form clearfix')) }}
    <div class="accordion">
    <?php
        $directions = Directions::whereActive(TRUE)->orderBy('order')->with('photo')
            ->with(array('courses'=>function($query){
                $query->where('active',1);
                $query->with('seo');
            }))->get();
    ?>
    @foreach($directions as $direction)
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
                    <th>
                        <div class="checkbox-container">
                            <input type="checkbox" autocomplete="off" class="main-checkbox">
                        </div>
                        Название
                    </th>
                    <th>Код</th>
                    <th>Часы</th>
                    <th>Цена</th>
                </tr>
            <?php $accountDiscount = getAccountDiscount(); ?>
            @foreach($direction->courses as $course)
                <tr>
                    <td>
                        <div class="checkbox-container">
                            <input type="checkbox" name="courses[]" autocomplete="off" value="{{ $course->id }}" class="secondary-checkbox">
                        </div>
                        {{ $course->title }}
                    </td>
                    <td>
                        <span class="code">{{ $course->code }}</span>
                    </td>
                    <td>
                        <span class="code">{{ $course->hours }}</span>
                    </td>
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
    <button type="submit" class="btn btn--bordered btn--blue pull-right btn-catalog js-btn-acc">Продолжить</button>
    {{ Form::close() }}
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop