{{ Form::open(array('url'=>URL::route('payment-order-number-update',$order->id), 'class'=>'auth-form registration-form clearfix margin-bottom-30', 'id'=>'payment-number-update-form', 'method'=>'PATCH')) }}
    {{ Form::hidden('payment_order_id',NULL,array('class'=>'js-edit-payment-id')) }}
    <div class="form-element js-edit-date">
        <label>Дата</label>{{ Form::text('payment_date','') }}
    </div>
    <div class="form-element js-edit-sum">
        <label>Сумма</label>{{ Form::text('price','') }}
    </div>
    <div class="form-element js-edit-num">
        <label>№ П/П</label>{{ Form::text('payment_number','') }}
    </div>
    <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue pull-right btn-form-submit">
        <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Изменить</span>
    </button>
{{ Form::close() }}