@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h1>{{ $page->block('top_h2') }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <h2 class="h3">{{ $page->block('center_h3') }}</h2>
    <ul class="lic-ul margin-top-30">
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