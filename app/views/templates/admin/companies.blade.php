@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список компаний</h2>
<div class="row">
@if(count($companies))    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
                <tr>
                    <th class="sort listeners-row sort--asc">Название <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Дата регистр. <span class="sort--icon"></span></th>
                    <th>Руководитель</th>
                    <th class="sort sort--asc">Заказы <span class="sort--icon"></span></th>
                    <th>Доход</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('admin-company-profile',$company['id']) }}">{{ $company['title'] }}</a><br>
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
                        реал.: {{ number_format($company['orders_earnings']['real_earnings'], 0, ',', ' ') }} руб.<br>
                        всего: {{ number_format($company['orders_earnings']['total_earnings'], 0, ',', ' ') }} руб.<br>
                        скидка: {{ $company['discount'] }}%.
                    </td>
                    <td>
                        <form method="DELETE" action="{{ URL::route('admin-company-profile-delete',array('company_id'=>$company['id'])) }}" style="display:inline-block">
                            <button type="submit" class="btn btn-danger remove-user" data-user-name="{{{ $company['title'] }}}">
                                Удалить
                            </button>
                        </form>
                    </td>
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
    <script src="{{ link::to('js/modules/users.js') }}"></script>
@stop