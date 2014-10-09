@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<?php $companies = User_organization::orderBy('created_at','DESC')->get(); ?>
<h1>Список компаний</h1>
<div class="row">
  @if($companies->count())
  
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Контактное лицо</th>
                    <th>Дата регистрации</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($companies as $company)
                <tr class="vertical-middle">
                    <td>{{ $company->title }}</td>
                    <td>{{ $company->name }}.{{ $company->email }} {{ $company->phone }}</td>
                    <td>{{ myDateTime::SwapDotDateWithTime($company->created_at) }}</td>
                    <td> </td>
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