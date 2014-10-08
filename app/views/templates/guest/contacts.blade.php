@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h2>{{ $page->block('top_h2') }}</h2>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>


</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop