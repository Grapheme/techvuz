@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="catalog">
    <h2>Оформление нового заказа. Шаг №2</h2>
    @if(Session::get('message'))
    <div class="banner banner--red">
        <span>{{ Session::get('message') }}</span>
    </div>
    @endif
@if(isOrganization() && hasCookieData('ordering'))
    <?php $listeners = User_listener::where('organization_id',Auth::user()->id)->where('active',1)->lists('fio','id'); ?>
    <h2>Покупка курсов</h2>
    <div>
        <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn--bordered btn--blue ">
           <span class="icon icon-kurs_dob"></span> Добавить курс
        </a>
        <a href="{{ URL::route('signup-listener') }}" class="btn btn--bordered btn--blue ">
           <span class="icon icon-kurs_dob"></span> Добавить сотрудника
        </a>
    </div>
    {{ Form::open(array('route'=>'ordering-listeners-store','class'=>'purchase-form clearfix')) }}
        <dl class="purchase-course-dl">
        @foreach(Courses::whereIn('id',getJsonCookieData('ordering'))->with('direction')->get() as $course)
            {{ Form::hidden('courses[]',$course->id) }}
            <dt class="purchase-course-dt">
                <table class="tech-table purchase-table" data-courseid="{{ $course->id }}">
                    <tr>
                        <th>Название</th>
                        <th>Код</th>
                        <th>Часы</th>
                        <th>Слушатели</th>
                        <th>Цена</th>
                    </tr>
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->hours }}</td>
                        <td class="purchase-listeners"></td>
                        <td class="purchase-price" data-price="{{ $course->price }}">{{ number_format($course->price,0,'.',' ')  }}.–</td>
                    </tr>
                </table>
            </dt>
            <dd class="purchase-course-dd">
                <select data-placeholder="Выберите пользователей" name="listeners[{{ $course->id }}][]" style="width:450px" multiple="multiple" class="chosen-select">
                @foreach($listeners as $listener_id => $listener_fio)
                    <option value="{{ $listener_id }}">{{ $listener_fio }}</option>
                @endforeach
                </select>
            </dd>
        @endforeach
        </dl>
        {{ Form::hidden('completed',1) }}
        <button type="submit" class="btn btn--bordered btn--blue pull-right js-coursebuy-finish-delete">Завершить</button>
    {{ Form::close() }}
@endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop