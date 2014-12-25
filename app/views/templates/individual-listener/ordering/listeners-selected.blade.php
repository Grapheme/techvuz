@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="catalog">
    <h1>Покупка курсов, шаг 2</h1>
    @if(Session::get('message'))
    <div class="banner banner--red">
        <span>{{ Session::get('message') }}</span>
    </div>
    @endif
@if(isIndividual() && hasCookieData('ordering'))
    <?php $listeners = User_individual::where('id',Auth::user()->id)->where('active','>=',1)->lists('fio','id'); ?>
    <div class="margin-bottom-20">
        <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn--bordered btn--blue margin-top-30">
           <span class="icon icon-kurs_dob"></span> Добавить курс
        </a>
    </div>
    {{ Form::open(array('route'=>'ordering-listeners-store','class'=>'purchase-form clearfix')) }}
        <dl class="purchase-course-dl" data-count-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount',TRUE)->property }}" data-value-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount-percent',TRUE)->property }}">
        <?php
            $accountDiscount = getAccountDiscount();
            $globalDiscount = getGlobalDiscount();
            $coursesCountDiscount = coursesCountDiscount();
            $totalPrice = 0;
        ?>
            <dt class="purchase-course-dt">
                <?php $discountStatic = 0; $useCourseDiscount = 0; ?>
                <table class="tech-table purchase-table" data-use-discount="{{ $notUseCourseDiscount }}" data-static-discount="{{ $discountStatic }}" data-courseid="{{ $course->id }}">
                    <tr>
                        <th>Название</th>
                        <th>Код</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                @foreach(Courses::whereIn('id',getJsonCookieData('ordering'))->with('direction')->get() as $course)
                    {{ Form::hidden('courses[]',$course->id) }}
                    <tr>
                        <td>
                            <div class="icon-blue-bag-btn js-delete-course"></div>
                            {{ $course->title }}
                        </td>
                        <td>{{ $course->code }}</td>
                        <?php
                            $discountPrice = FALSE;
                            $useCourseDiscount = 0;
                        ?>
                        @if($course->direction->use_discount && $course->use_discount)
                            <?php $useCourseDiscount = 1;?>
                            <?php $discountPrice = calculateDiscount(array($course->direction->discount,$course->discount,$accountDiscount,$globalDiscount),$course->price); ?>
                        @endif
                    @if($discountPrice === FALSE || $discountPrice == $course->price)
                        <?php $totalPrice += $course->price; ?>
                        <td class="purchase-price" data-price="{{ number_format($course->price,0,'.','') }}">{{ number_format($course->price,0,'.',' ') }}.–</td>
                        <td class="purchase-price-sum">{{ number_format($course->price,0,'.','') }}.–</td>
                    @else
                        <?php $totalPrice += $discountPrice; ?>
                        <td class="purchase-price" data-price="{{ number_format($discountPrice,0,'.','') }}">{{ number_format($discountPrice,0,'.',' ') }}.–</td>
                        <td class="purchase-price-sum">{{ number_format($discountPrice,0,'.','') }}.–</td>
                    @endif
                    </tr>
                    <dd class="purchase-course-dd hidden">
                        <select name="listeners[{{ $course->id }}][]" multiple="multiple">
                        @foreach($listeners as $listener_id => $listener_fio)
                            <option selected value="{{ $listener_id }}">{{ $listener_fio }}</option>
                        @endforeach
                        </select>
                    </dd>
                @endforeach
                </table>
            </dt>
        </dl>
        <div class="sum-block margin-bottom-40">
            <div class="count-add">
                <div class="container-fluid">
                    <div class="row no-gutter margin-bottom-20">
                        <div class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="count-add-sign">Итоговая сумма</div>
                            <div class="count-add-num js-count-finish-sum">{{ number_format($totalPrice,0,'.','') }}.–</div>
                            <div class="count-add-dots"></div>
                        </div>
                    </div>                                
                </div>
            </div>
        </div>
        {{ Form::hidden('completed',1) }}
        <button type="submit" class="btn btn--bordered btn--blue pull-right js-coursebuy-finish">Заказать</button>
    {{ Form::close() }}
@endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop