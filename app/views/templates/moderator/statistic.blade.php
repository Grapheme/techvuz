@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2>Статистика</h2>
<div class="row">
    <div class="employee-search input">
        @include(Helper::acclayout('forms.statistic'))
    </div>
</div>
@if(count($users))
<h3 class="margin-bottom-40">Статистика заказов</h3>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Название/ФИО</th>
                    <th>Руководитель/Должность</th>
                    <th>Заказы</th>
                    <th>Доход</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr class="vertical-middle">
                    <td>
                    @if($user['group'] == 4)
                        <a href="{{ URL::route('moderator-company-profile',$user['id']) }}">{{ $user['title'] }}</a>
                    @elseif($user['group'] == 6)
                        <a href="javascript:void(0)">{{ $user['title'] }}</a>
                    @endif
                        <br>рег.: {{ $user['created_at'] }}
                    </td>
                    <td>
                        {{ $user['manager'] }}<br>
                        {{ $user['fio_manager'] }}<br>
                        {{ $user['email'] }}
                        {{ $user['phone'] }}
                    </td>
                    <td>{{ $user['orders_count'] }}</td>
                    <td>
                        всего: {{ number_format($user['real_earnings'], 0, ',', ' ') }} руб.<br>
                        скидка: {{ $user['discount'] }}%.
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@if(count($courses))
<h3 class="margin-bottom-40">Статистика курсов</h3>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Код. Название</th>
                    <th>Цена/Скидка</th>
                    <th>Доход</th>
                </tr>
            </thead>
            <tbody>
            @foreach($courses as $course)
                <tr class="vertical-middle">
                    <td>{{ $course['code'] }}. {{ $course['title'] }}</td>
                    <td>
                        {{ number_format($course['price'], 0, ',', ' ') }} руб.<br>
                        {{ $course['discount'] }}%.
                    </td>
                    <td>{{ number_format($course['real_earnings'], 0, ',', ' ') }} руб.</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/vendor/jquery.ui.datepicker-ru.js') }}
<script>
    $(function(){
        $("#select-period-begin").datepicker({
            constrainInput: true,
            autoSize: true,
            firstDay: 1,
            minDate: "01.10.2014",
            maxDate: '0D',
            defaultDate: "-2w",
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate){
                $("#select-period-end").datepicker("option","minDate",selectedDate);
            }
        });
        $("#select-period-end").datepicker({
            constrainInput: true,
            autoSize: true,
            firstDay: 1,
            defaultDate: "0D",
            minDate: "01.10.2014",
            maxDate: '0D',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function(selectedDate){
                $("#select-period-begin").datepicker("option","maxDate",selectedDate);
            }
        });
    });
</script>
@stop
@stop