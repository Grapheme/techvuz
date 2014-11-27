@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->title }}</h2>
<div class="row">
    <h3 class="margin-bottom-30">Редактировать профиль компании</h3>
    @include(Helper::acclayout('forms.company-profile'))
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop