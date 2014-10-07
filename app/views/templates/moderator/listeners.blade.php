@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php $listeners = User_listener::orderBy('created_at','DESC')->get(); ?>
<h1>Список слушателей</h1>
<div class="row">
  @if($listeners->count())
  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
          <table class="table table-striped table-bordered">
              <thead>
                  <tr>
                      <th>Ф.И.О.</th>
                      <th>Контактные данные</th>
                      <th>Дата регистрации</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
              @foreach($listeners as $listener)
                  <tr class="vertical-middle">
                      <td>{{ $listener->fio }}</td>
                      <td>{{ $listener->email }} {{ $listener->phone }}</td>
                      <td>{{ myDateTime::SwapDotDateWithTime($listener->created_at) }}</td>
                      <td> </td>
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