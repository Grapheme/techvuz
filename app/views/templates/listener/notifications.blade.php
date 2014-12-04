@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
    <?php $account = User_listener::where('id',Auth::user()->id)->with('organization')->first(); ?>
    <h1>{{ $account->fio }}</h1>
    <p class="style-light style-italic">{{ $account->organization->title }}</p>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employees margin-bottom-40">
            <h3 class="margin-bottom-20">Уведомления</h3>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop