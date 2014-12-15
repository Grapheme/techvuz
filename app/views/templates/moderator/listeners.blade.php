@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Список слушателей</h2>
<div class="row">
@if(count($listeners))
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <form class="employee-search margin-bottom-20">
            <fieldset>
                <input type="text" placeholder="Поиск по слушателям">
                <button type="submit"><span class="icon icon-search"></span></button>
            </fieldset>
        </form>
        <table class="tech-table table table-striped table-bordered sortable">
            <thead>
                <tr>
                    <th class="sort listeners-row sort--asc">Ф.И.О. <span class="sort--icon"></span></th>
                    <th class="sort sort--asc">Контактные данные <span class="sort--icon"></span></th>
                    <th class="sort sort--asc">Компания <span class="sort--icon"></span></th>
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
                    @else
                        Индивидуальный слушатель
                    @endif
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