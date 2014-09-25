{{ Form::open(array('url'=>URL::route('signup-fl'), 'role'=>'form', 'class'=>'registration-form', 'id'=>'signup-fl-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','individual')->first()->id) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <fieldset>
        <div class="form-element">
            <label>Ф.И.О.</label>{{ Form::text('fio', '') }}
        </div>
        <div class="form-element">
            <label>Должность</label>{{ Form::text('position', '') }}
        </div>
        <div class="form-element">
            <label>ИНН</label>{{ Form::text('inn', '') }}
        </div>
        <div class="form-element">
            <label>Почтовый адрес</label>{{ Form::text('postaddress', '') }}
        </div>
        <div class="form-element">
            <label>E-mail</label>{{ Form::text('email', '',array('class'=>'email')) }}
        </div>
        <div class="form-element">
            <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
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