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
@if(isOrganization() && hasCookieData('ordering'))
    <?php $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active','>=',1)->lists('fio','id'); ?>
    <div class="margin-bottom-20">
        <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn--bordered btn--blue margin-top-30">
           <span class="icon icon-kurs_dob"></span> Добавить курс
        </a>
        <a href="{{ URL::route('signup-listener') }}" class="btn btn--bordered btn--blue margin-top-30">
           <span class="icon icon-kurs_dob"></span> Добавить сотрудника
        </a>
    </div>
    {{ Form::open(array('route'=>'ordering-listeners-store','class'=>'purchase-form clearfix')) }}
        <dl class="purchase-course-dl" data-count-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount',TRUE)->property }}" data-value-discount = "{{ Dictionary::valueBySlugs('properties-site','count-by-course-discount-percent',TRUE)->property }}">
        <?php
            $accountDiscount = getAccountDiscount();
            $coursesCountDiscount = coursesCountDiscount(Courses::whereIn('id',getJsonCookieData('ordering'))->get());
        ?>
        @foreach(Courses::whereIn('id',getJsonCookieData('ordering'))->with('direction')->get() as $course)
            {{ Form::hidden('courses[]',$course->id) }}
            <dt class="purchase-course-dt">
                <table class="tech-table purchase-table" data-courseid="{{ $course->id }}">
                    <tr>
                        <th>Название</th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Сотрудники</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->hours }}</td>
                        <td class="purchase-listeners"></td>
                        <?php
                        $discountPrice = calculateDiscount(array($course->direction->discount,$course->discount,$accountDiscount,$coursesCountDiscount),$course->price);
                        ?>
                        @if($discountPrice === FALSE)
                            <td class="purchase-price" data-price="{{ $course->price }}">{{ number_format($course->price,0,'.',' ')  }}.–</td>
                        @else
                            <td class="purchase-price" data-price="{{ $discountPrice }}">{{ number_format($discountPrice,0,'.',' ')  }}.–</td>
                        @endif
                    </tr>
                </table>
            </dt>
            <dd class="purchase-course-dd">
                <select data-placeholder=" " name="listeners[{{ $course->id }}][]" style="width:450px" multiple="multiple" class="chosen-select">
                @foreach($listeners as $listener_id => $listener_fio)
                    <option value="{{ $listener_id }}">{{ $listener_fio }}</option>
                @endforeach
                </select>
            </dd>
        @endforeach
        </dl>
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