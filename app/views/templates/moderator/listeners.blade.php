@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список слушателей</h2>
<div class="row">
@if(count($listeners))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Контактные данные</th>
                    <th>Компания</th>
                </tr>
            </thead>
            <tbody>
            @foreach($listeners as $listener)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('moderator-listener-profile',$listener['id']) }}">{{ $listener['fio'] }}</a><br>
                        рег.: {{ myDateTime::SwapDotDateWithTime($listener['created_at']) }}
                    </td>
                    <td>
                        {{ $listener['email'] }}<br>
                        {{ $listener['phone'] }}
                    </td>
                    <td>
                    @if(isset($listener['organization']))
                        <a href="{{ URL::route('moderator-company-profile',$listener['organization']['id']) }}">{{ $listener['organization']['title'] }}</a>
                    @endif
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
@stop