@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<h2 class="margin-bottom-40">{{ $profile->fio }}</h2>
@if($profile['group_id'] == 5 && isset($profile->organization))
    <p class="style-light style-italic">{{ $profile->organization->title }}</p>
@endif
<div class="row">

    <?php $accountStatus = array('Не активный','Активный','Не активирован')?>
    @if(isset($accountStatus[$profile->active]))
        @if($profile->active == 2)
            <?php $activation_date = '. До '.date("d.m.Y H:i:s",User::where('id',$profile->id)->pluck('code_life'));?>
        @else
            <?php $activation_date = ''; ?>
        @endif
        <a class="icon--blue pull-right margin-top-30" href="{{ URL::route('moderator-listener-profile-edit',$profile->id) }}">
            <span class="icon icon-red"></span>
        </a>
        <h3>Профиль</h3>
        <div class="style-light style-italic">
            {{ $accountStatus[$profile->active] }}{{ $activation_date }}
        </div>
        @if($profile->group_id == 6)
            <div class="style-light style-italic">
                Модератором {{ $profile->moderator_approve ? 'подтвержден' : 'не подтвержден' }}
            </div>
        @endif
    @endif

    <div class="employer-anket margin-top-30 margin-bottom-40">
        <div class="employer-anket margin-bottom-40">
        @if($profile->group_id == 5)
            @include(Helper::acclayout('assets.profiles.listener'))
        @elseif($profile->group_id == 6)
            @include(Helper::acclayout('assets.profiles.individual'))
        @endif
        </div>
    </div>
</div>
@stop
@section('overlays')
@stop
@section('scripts')
@stop