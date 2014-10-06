
@extends(Helper::acclayout())
@section('style')
@stop
@section('content')

<main class="cabinet">
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="cabinet-tabs">
        @include(Helper::acclayout('menu'))
        <div class="employer-anket">
            <h3>Профиль</h3>
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop