{{ Form::open(array('url'=>URL::route('signup-ul'), 'role'=>'form', 'class'=>'registration-form', 'id'=>'signup-ul-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','organization')->first()->id) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <fieldset>
        <div class="form-element">
            <label>Наименование учреждения</label>{{ Form::text('title', '') }}
        </div>
        <div class="form-element">
            <label>Ф.И.О. ответственного лица</label>{{ Form::text('fio_manager', '') }}
        </div>
        <div class="form-element">
            <label>Должность</label>{{ Form::text('manager', '') }}
        </div>
        <div class="form-element">
            <label>Уставной документ</label>{{ Form::text('statutory', '') }}
        </div>
        <div class="form-element">
            <label>ИНН</label>{{ Form::text('inn', '') }}
        </div>
        <div class="form-element">
            <label>КПП</label>{{ Form::text('kpp', '') }}
        </div>
        <div class="form-element">
            <label>Почтовый адрес</label>{{ Form::text('postaddress', '') }}
        </div>
        <div class="form-element">
            <label>Тип счёта</label>{{ Form::select('account_type',AccountTypes::lists('title','id'),0,array('class'=>'select')) }}
        </div>
        <div class="form-element">
            <label>Номер счета</label>{{ Form::text('account_number', '') }}
        </div>
        <div class="form-element">
            <label>Наименование банка</label>{{ Form::text('bank', '') }}
        </div>
        <div class="form-element">
            <label>БИК</label>{{ Form::text('bik', '') }}
        </div>
    </fieldset>
    <fieldset>
        <header>Контактные данные</header>
        <div class="form-element">
            <label>E-mail</label>{{ Form::text('email', '') }}
        </div>
        <div class="form-element">
            <label>Контактное лицо</label>{{ Form::text('name', '') }}
        </div>
        <div class="form-element">
            <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
        </div>
    </fieldset>
    <fieldset>
        <div class="form-element">
            <label>Даю согласие на обработку персональных данных</label>{{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent-ul')) }}
        </div>
        <div class="form-element">
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
            </button>
        </div>
    </fieldset>
{{ Form::close() }}