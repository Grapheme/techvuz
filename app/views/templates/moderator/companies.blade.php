@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список компаний</h2>
@if(count($companies))
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form class="company-search margin-bottom-20">
            <fieldset>
                <input type="text" placeholder="Поиск по компаниям">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
                <tr>
                    <th class="sort listeners-row sort--asc">Название <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Дата регистр. <span class="sort--icon"></span></th>
                    <th>Руководитель</th>
                    <th class="sort sort--asc">Заказы <span class="sort--icon"></span></th>
                    <th>Доход</th>
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
                    <td>{{ $company['orders_count'] }}</td>
                    <td>
                        <nobr>реал.: {{ number_format($company['orders_earnings']['real_earnings'], 0, ',', ' ') }} руб.<br></nobr>
                        <nobr>всего: {{ number_format($company['orders_earnings']['total_earnings'], 0, ',', ' ') }} руб.<br></nobr>
                        <nobr>скидка: {{ $company['discount'] }}%.</nobr>
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