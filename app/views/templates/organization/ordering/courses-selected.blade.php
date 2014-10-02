@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="catalog">
    @if(Session::get('message'))
    <div class="banner banner--red">
        <span>{{ Session::get('message') }}</span>
    </div>
    @endif
@if(isOrganizationORIndividual() && hasCookieData('ordering'))
    <?php $courses = getJsonCookieData('ordering');?>
    <?php $coursesIDs = !empty($courses) ? array_keys($courses) : array();?>
    <h2>Покупка курсов</h2>
    <div>
        <a href="{{ URL::route('page','catalog') }}" class="btn btn--bordered btn--blue ">
           <span class="icon icon-kurs_dob"></span> Добавить курс
        </a>
        <a href="{{ URL::route('signup-listener') }}" class="btn btn--bordered btn--blue ">
           <span class="icon icon-kurs_dob"></span> Добавить сотрудника
        </a>
    </div>
    {{ Form::open(array('route'=>'ordering-courses-store','class'=>'purchase-form clearfix')) }}
        <dl class="purchase-course-dl">
        @foreach(Courses::whereIn('id',$coursesIDs)->with('direction')->get() as $course)
            <dt class="purchase-course-dt">
                <table class="table purchase-table">
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
                <select data-placeholder="Выберите пользователей" style="width:450px" multiple="multiple" class="chosen-select">
                    <option value="1">Романов Николай Павлович</option>
                    <option value="2">Романова Мария Федоровна</option>
                    <option value="3">Романова Анна Петровна</option>
                    <option value="4">Романов Константин Николаевич</option>
                    <option value="5">Романов Николай Константинович</option>
                    <option value="6">Романов Николай Павлович</option>
                    <option value="7">Романова Мария Федоровна</option>
                    <option value="8">Романова Анна Петровна</option>
                    <option value="9">Романов Константин Николаевич</option>
                    <option value="10">Романов Николай Константинович</option>
                    <option value="11">Романов Николай Павлович</option>
                    <option value="12">Романова Мария Федоровна</option>
                    <option value="13">Романова Анна Петровна</option>
                    <option value="14">Романов Константин Николаевич</option>
                    <option value="15">Романов Николай Константинович</option>
                </select>
            </dd>
        @endforeach
        </dl>
        <button type="submit" class="btn btn--bordered btn--blue pull-right">Далее</button>
    {{ Form::close() }}
@endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop