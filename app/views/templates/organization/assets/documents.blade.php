@if(User_organization::where('id',Auth::user()->id)->pluck('moderator_approve') == 1)
<div class="order-documents container-fluid">
    <ul class="row order-docs-ul no-gutter">
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head docs">
                    <a href="{{ URL::route('organization-order-contract',array('order_id'=>$order->id,'format'=>'pdf')) }}">Договор</a>
                </div>
                <div class="order-docs-body">
                    <h2>Договор</h2>
                    <p>
                        Просмотреть данный документ вы можете нажав на него.
                    </p>
                </div>
            </div>                            
        </li>
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head bill">
                    <a href="{{ URL::route('organization-order-invoice',array('order_id'=>$order->id,'format'=>'pdf')) }}">Счет</a>
                </div>
                <div class="order-docs-body">
                    <h2>Счет</h2>
                    <p>
                        Документ доступен к просмотру после оплаты заказа.
                    </p>
                </div>
            </div>                            
        </li>
        @if(is_object($order) && $order->close_status == 1)
        <li class="col-xs-4 col-sm-4 col-md-4 col-lg-4 order-docs-li">
            <div class="order-docs-cont">
                <div class="order-docs-head acts">
                    <a href="{{ URL::route('organization-order-act',array('order_id'=>$order->id,'format'=>'pdf')) }}">Акт</a>
                </div>
                <div class="order-docs-body">
                    <h2>Акт</h2>
                    <p>
                        Документ доступен к просмотру после оплаты заказа.
                    </p>
                </div>
            </div>                            
        </li>
        @endif
    </ul>
</div>
@endif