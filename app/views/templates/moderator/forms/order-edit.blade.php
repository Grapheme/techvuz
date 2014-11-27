{{ Form::model($order,array('url'=>URL::route('moderator-order-update',$order->id), 'class'=>'registration-form','id'=>'order-edit-form', 'files'=>TRUE, 'method'=>'PATCH')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Номер</label>{{ Form::text('number') }}
    </div>
    <div class="form-element">
        <label>Дата оформления</label>{{ Form::text('created_at',$order->created_at->format('d.m.Y H:i:s')) }}
    </div>
    <div class="form-element">
        <label>Дата оплаты</label>{{ Form::text('payment_date',(new myDateTime())->setDateString($order->payment_date)->format('d.m.Y H:i:s')) }}
    </div>
    <div class="form-element">
        <label>Договор</label>{{ ExtForm::upload('contract',$order->contract_id) }}
    </div>
    <div class="form-element">
        <label>Счет</label>{{ ExtForm::upload('invoice',$order->invoice_id) }}
    </div>
    <div class="form-element">
        <label>Акт</label>{{ ExtForm::upload('act',$order->act_id) }}
    </div>
    <div class="form-element">
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
        </button>
        <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
    </div>
{{ Form::close() }}