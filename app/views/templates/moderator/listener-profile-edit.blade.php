@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->title }}</h2>
<div class="row">
    <h3 class="margin-bottom-30">Редактировать профиль слушателя</h3>
    @if($profile_group_id == 5)
        @include(Helper::acclayout('forms.company-listener-profile'))
    @elseif($profile_group_id == 6)
        @include(Helper::acclayout('forms.individual-listener-profile'))
    @endif
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop