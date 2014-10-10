@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список компаний</h2>
<div class="row">
@if(count($companies))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Руководитель</th>
                    <th>Заказы</th>
                    <th>Доход</th>
                </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('moderator-company-profile',$company['id']) }}">{{ $company['title'] }}</a><br>
                        рег.: {{ $company['created_at'] }}
                    </td>
                    <td>
                        {{ $company['manager'] }}<br>
                        {{ $company['fio_manager'] }}<br>
                        {{ $company['email'] }}
                    </td>
                    <td>{{ $company['orders_count'] }}</td>
                    <td>
                        реал.: {{ number_format($company['orders_earnings']['real_earnings'], 0, ',', ' ') }} руб.<br>
                        всего: {{ number_format($company['orders_earnings']['total_earnings'], 0, ',', ' ') }} руб.
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif
@stop
@section('overlays')
@stop
@section('scripts')
@stop