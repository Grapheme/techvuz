@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список тестовых аккаунтов</h2>
@if(count($companies))
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h5 class="margin-bottom-40">Тестовые организации</h5>
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
                <tr>
                    <th class="sort listeners-row sort--asc">Название </th>
                    <th class="sort sort--asc">Дата регистр.</th>
                    <th>Руководитель</th>
                </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('moderator-company-profile',$company['id']) }}">{{ $company['title'] }}</a><br>
                    </td>
                    <td>
                        {{ (new myDateTime())->setDateString($company['created_at'])->format('Y.m.d в H:i') }}
                    </td>
                    <td>
                        {{ $company['manager'] }}<br>
                        {{ $company['fio_manager'] }}<br>
                        {{ $company['email'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p class="hidden js-search-table-error font-sm text-center margin-top-20">Ничего не найдено</p>
    </div>
</div>
@endif
@if(count($listeners))
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h5 class="margin-bottom-40">Тестовые индивидуальные слушатели</h5>
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
            <tr>
                <th class="sort listeners-row sort--asc">Ф.И.О.</th>
                <th class="sort sort--asc">Дата регистр.</th>
                <th class="sort sort--asc">Контактные данные</th>
            </tr>
            </thead>
            <tbody>
            @foreach($listeners as $listener)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('moderator-listener-profile',$listener['id']) }}">{{ $listener['fio'] }}</a><br>
                    </td>
                    <td>
                        {{ (new myDateTime())->setDateString($listener['created_at'])->format('d.m.Y в H:i') }}
                    </td>
                    <td>
                        {{ $listener['email'] }}<br>
                        {{ $listener['phone'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p class="hidden js-search-table-error font-sm text-center margin-top-20">Ничего не найдено</p>
    </div>
</div>
@endif
@stop
@section('overlays')
@stop
@section('scripts')
@stop