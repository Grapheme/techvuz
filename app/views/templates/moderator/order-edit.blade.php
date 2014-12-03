@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">Редактирование заказа №{{ getOrderNumber($order) }}</h2> {{ $order->created_at->timezone(Config::get('site.time_zone'))->format("d.m.Y в H:i") }}
<div class="row">
    @include(Helper::acclayout('forms.order-edit'))
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop