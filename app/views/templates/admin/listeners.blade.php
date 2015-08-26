@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список слушателей</h2>
<div class="row">
@if(count($listeners))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
                <tr>
                    <th class="sort listeners-row sort--asc">Ф.И.О. <span class="sort--icon"></span></th>
                    <th class="sort sort--asc">Контактные данные <span class="sort--icon"></span></th>
                    <th class="sort sort--asc">Компания <span class="sort--icon"></span></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($listeners as $listener)
                <tr class="vertical-middle">
                    <td>
                        <a href="{{ URL::route('admin-listener-profile',$listener['id']) }}">{{ $listener['fio'] }}</a><br>
                        рег.: {{ (new myDateTime())->setDateString($listener['created_at'])->format('d.m.Y в H:i') }}
                    </td>
                    <td>
                        {{ $listener['email'] }}<br>
                        {{ $listener['phone'] }}
                    </td>
                    <td>
                    @if(isset($listener['organization']))
                        <a href="{{ URL::route('admin-company-profile',$listener['organization']['id']) }}">{{ $listener['organization']['title'] }}</a>
                    @else
                        Индивидуальный слушатель
                    @endif
                    </td>
                    <td>
                        <form method="DELETE" action="{{ URL::route('admin-listener-profile-delete',array('listener_id'=>$listener['id'])) }}" style="display:inline-block">
                            <button type="submit" class="btn btn-danger remove-user" data-user-name="{{{ $listener['fio'] }}}">
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