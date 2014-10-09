@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h2>{{ $page->block('top_h2') }}</h2>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <h3>{{ $page->block('center_h3') }}</h3>
    <ul class="lic-ul margin-top-40">
        <li class="lic-li">
            <a href="#"></a>
        </li>
        <li class="lic-li">
            <a href="#"></a>
        </li>
        <li class="lic-li">
            <a href="#"></a>
        </li>
        <li class="lic-li">
            <a href="#"></a>
        </li>
        <li class="lic-li">
            <a href="#"></a>
        </li>
        <li class="lic-li">
            <a href="#"></a>
        </li>
    </ul>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop