@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <?php
    $orders = Orders::orderBy('payment_status')->orderBy('created_at','DESC')->with('payment')->with(array('listeners'=>function($query){
        $query->with('listener');
        $query->with('course');
    }))->get();
    ?>
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        <ul>
            <li><a href="{{ URL::route('company-orders') }}"><span class="icon icon-zakaz"></span> Заказы</a></li>
            <li><a href="{{ URL::route('company-listeners') }}"><span class="icon icon-slysh"></span> Сотрудники</a></li>
            <li><a href="{{ URL::route('company-study') }}"><span class="icon icon-obych"></span> Обучение</a></li>
            <li><a href="{{ URL::route('company-notifications') }}"><span class="icon icon-yved"></span> Уведомления</a></li>
        </ul>
        <div class="employees">
            <h3>Сотрудники</h3>
            <form class="employee-search">
                <fieldset>
                    <input type="text" placeholder="Укажите ФИО сотрудника, название компании или курса">
                    <button type="submit"><span class="icon icon-search"></span></button>
                </fieldset>
            </form>
            <table class="table sortable">
                <tr>
                    <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                </tr>
                <tr>
                    <td>
                        <a href="#">Васильев Валерий Михайлович</a>
                    </td>
                    <td>
                        Не самое длинное название одного из курсов системы
                        <a class="more-courses" href="#">показать еще 2 курса</a>
                    </td>
                    <td class="td-status-bar">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                        <div class="bar-part bar-part-1"></div>
                        <div class="bar-part bar-part-2"></div>
                        <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop