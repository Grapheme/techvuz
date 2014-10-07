@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main class="cabinet">
     <h2>{{ Auth::user()->name.' '.Auth::user()->surname }}</h2>
     <div class="cabinet-tabs">
         @include(Helper::acclayout('menu'))
     </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop