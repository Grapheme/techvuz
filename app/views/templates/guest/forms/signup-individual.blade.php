{{ Form::open(array('url'=>URL::route('signup-fl'), 'class'=>'registration-form', 'id'=>'signup-fl-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','individual')->pluck('id')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <fieldset>
        <div class="form-element">
            <label>Ф.И.О.</label>{{ Form::text('fio', '',array('placeholder'=>'Иванов Иван Иванович')) }}
        </div>
        <div class="form-element">
            <label>Ф.И.О. в род. падеже</label>{{ Form::text('fio_rod', '',array('placeholder'=>'Иванов Иван Иванович')) }}
        </div>
        <div class="form-element">
            <label>Серия паспорта</label>{{ Form::text('passport_seria', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Номер паспорта</label>{{ Form::text('passport_number', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Кем выдан</label>{{ Form::text('passport_data', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Дата выдачи</label>{{ Form::text('passport_date', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Код подразделения</label>{{ Form::text('code', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Зарегистрирован по адресу</label>{{ Form::text('postaddress', '',array('placeholder'=>'121354, г. Москва, ул. Кутузова, 57, кв. 9')) }}
        </div>
         <div class="form-element">
            <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
        </div>
        <div class="form-element">
            <label>E-mail</label>{{ Form::email('email', '',array('placeholder'=>'')) }}
        </div>
    </fieldset>
    <fieldset>
        <div class="form-element">
            <label>Даю согласие на обработку персональных данных</label>{{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent-fz')) }}
        </div>
        <div class="form-element">
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
            </button>
        </div>
    </fieldset>
{{ Form::close() }}