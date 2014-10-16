@extends(Helper::acclayout())
@section('style')
@stop
@section('content')
<main>
    @if(isset($order))
        <?php extract($order, EXTR_PREFIX_ALL, "order");?>
    @endif
    @if(isset($account))
        <?php extract($account, EXTR_PREFIX_ALL, "company");?>
    @endif
    @if(isset($listener))
        <?php extract($listener, EXTR_PREFIX_ALL, "listener");?>
    @endif
    @if(File::exists($template))
        <?php require_once($template);?>
    @endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop