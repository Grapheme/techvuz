@if(User_organization::where('id',Auth::user()->id)->pluck('moderator_approve') == 1)
<div class="margin-top-40 order-documents container-fluid">
    <ul class="row order-docs-ul no-gutter">
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head docs">
                    <a class="reset-link" href="{{ URL::route('organization-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}"></a>
                </div>
                <div class="order-docs-body">
                    <h2>Договор</h2>
                    <p class="font-sm">
                        Нажмите на документ, чтобы загрузить его.
                    </p>
                </div>
            </div>                            
        </li>
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head bill">
                    <a class="reset-link" href="{{ URL::route('organization-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}"></a>
                </div>
                <div class="order-docs-body">
                    <h2>Счет</h2>
                    <p class="font-sm">
                        Нажмите на документ, чтобы загрузить его.
                    </p>
                </div>
            </div>                            
        </li>
        @if(is_object($order) && $order->close_status == 1)
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head acts">
                    <a class="reset-link" href="{{ URL::route('organization-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}"></a>
                </div>
                <div class="order-docs-body">
                    <h2>Акт</h2>
                    <p class="font-sm">
                        Нажмите на документ, чтобы загрузить его.
                    </p>
                </div>
            </div>                            
        </li>
        @else
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head acts">
                    <a class="reset-link"></a>
                </div>
                <div class="order-docs-body">
                    <h2>Акт</h2>
                    <p class="font-sm">
                        Документ будет доступен после завершения обучения.
                    </p>
                </div>
            </div>                            
        </li>
        @endif
    </ul>
</div>
@endif