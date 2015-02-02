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
    <dl class="purchase-course-dl js-individual individual" data-count-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount',TRUE)->property }}" data-value-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount-percent',TRUE)->property }}">
        <?php
        $accountDiscount = getAccountDiscount();
        $globalDiscount = getGlobalDiscount();
        $coursesCountDiscount = coursesCountDiscount();
        ?>
        @foreach(Courses::whereIn('id',getJsonCookieData('ordering'))->with('direction')->get() as $key => $course)
            {{ Form::hidden('courses[]',$course->id) }}
            <?php
            $discountPrice = FALSE;
            $useCourseDiscount = 0;
            ?>
            @if($course->direction->use_discount && $course->use_discount)
                <?php $useCourseDiscount = 1;?>
                <?php $discountPrice = calculateDiscount(array($course->direction->discount,$course->discount,$globalDiscount,$accountDiscount),$course->price); ?>
                <?php $discountStatic = calculateDiscount(array($course->direction->discount,$course->discount,$globalDiscount,$accountDiscount)); ?>
            @endif
            <dt class="purchase-course-dt">
            <table class="tech-table purchase-table table-{{ $key }}" data-use-discount="{{ $useCourseDiscount }}" data-static-discount="{{ $discountStatic }}" data-courseid="{{ $course->id }}">
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Цена</th>
                    <th>Сотрудники</th>
                    <th>Сумма</th>
                </tr>
                <tr>
                    <td>
                        <div class="icon-blue-bag-btn js-delete-course" title="Удалить курс"></div>
                        {{ $course->title }}
                    </td>
                    <td>{{ $course->code }}</td>
                    @if($discountPrice === FALSE || $discountPrice == $course->price)
                        <td class="purchase-price" data-real-price="{{ $course->price }}" data-price="{{ $course->price }}">
                            <div class="start-price">
                                {{ number_format($course->price,0,'.',' ') }}.–
                            </div>
                        </td>
                    @else
                        <td class="purchase-price" data-real-price="{{ $course->price }}" data-price="{{ $discountPrice }}">
                            <div class="start-price margin-bottom-10" style="text-decoration: line-through;">
                                {{ number_format($course->price,0,'.',' ') }}.–
                            </div>
                            <div class="discount-price">
                                {{ number_format($discountPrice,0,'.',' ') }}.–
                            </div>
                        </td>
                    @endif

                    <td class="purchase-listeners"></td>

                    <td class="purchase-price-sum">0.–</td>
                </tr>
            </table>
            </dt>
            <dd class="purchase-course-dd hidden">
                <select data-placeholder=" " name="listeners[{{ $course->id }}][]" style="width:450px" multiple="multiple" class="chosen-select">
                    @foreach($listeners as $listener_id => $listener_fio)
                        <option value="{{ $listener_id }}">{{ $listener_fio }}</option>
                    @endforeach
                </select>
            </dd>
        @endforeach
    </dl>
    <div class="sum-block margin-bottom-40">
        <div class="count-add">
            <div class="container-fluid">
                <div class="row no-gutter margin-bottom-20 hidden">
                    <div class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="count-add-sign">Сумма</div>
                        <div class="count-add-num js-count-finish-sum-wo-discount"></div>
                        <div class="count-add-dots"></div>
                    </div>
                </div>
                <div class="row no-gutter margin-bottom-20 hidden">
                    <div class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6 icon--blue">
                        <div class="count-add-sign">Скидка</div>
                        <div class="count-add-num js-count-finish-discount"></div>
                        <div class="count-add-dots"></div>
                    </div>
                </div>
                <div class="row no-gutter margin-bottom-20">
                    <div class="col-xs-offset-6 col-sm-offset-6 col-md-offset-6 col-lg-offset-6 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="count-add-sign">К оплате</div>
                        <div class="count-add-num js-count-finish-sum"></div>
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