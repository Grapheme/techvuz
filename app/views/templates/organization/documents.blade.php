@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main>
<?php
    extract($order, EXTR_PREFIX_ALL, "order");
    extract($account, EXTR_PREFIX_ALL, "company");
?>
    @if(File::exists($template))
        <?php require_once($template);?>
    @endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop