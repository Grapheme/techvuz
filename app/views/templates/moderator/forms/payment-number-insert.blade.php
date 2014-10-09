{{ Form::open(array('url'=>URL::route('payment-order-number-store',$order->id), 'class'=>'auth-form registration-form clearfix margin-bottom-30', 'id'=>'payment-number-insert-form', 'method'=>'POST')) }}
    {{ Form::hidden('order_id',$order->id) }}
        <div class="form-element">
            <label>Дата</label>{{ Form::text('payment_date',date("d.m.Y")) }}
        </div>
        <div class="form-element">
            <label>Сумма</label>{{ Form::text('price','') }}
        </div>
        <div class="form-element">
            <label>№ П/П</label>{{ Form::text('payment_number','') }}
        </div>
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue pull-right btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Добавить</span>
        </button>
{{ Form::close() }}