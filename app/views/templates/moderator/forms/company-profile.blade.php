{{ Form::model($profile,array('url'=>URL::route('moderator-company-profile-update',$profile->id), 'class'=>'registration-form', 'id'=>'profile-company-form', 'method'=>'PATCH')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Email</label>{{ Form::text('email') }}
    </div>
    <div class="form-element">
        <label>Статус аккаунта</label>{{ Form::select('active',array('Не активный','Активный','Не активирован'),NULL,array('class'=>'select')) }}
    </div>

    <div class="form-element">
        <label>Полное наименование организации</label>{{ Form::text('title') }}
    </div>
    <div class="form-element">
        <label>ФИО подписанта договора</label>{{ Form::text('fio_manager') }}
    </div>
     <div class="form-element">
        <label>ФИО подписанта договора в род. падеже</label>{{ Form::text('fio_manager_rod') }}
    </div>
    <div class="form-element">
        <label>Должность подписанта договора</label>{{ Form::text('manager') }}
    </div>
    <div class="form-element">
        <label>Документ, на основании которого действует подписант</label>{{ Form::text('statutory') }}
    </div>
    <div class="form-element">
        <label>Юридический адрес</label>{{ Form::text('uraddress') }}
    </div>
    <div class="form-element">
        <label>Почтовый адрес</label>{{ Form::text('postaddress') }}
    </div>
    <div class="form-element">
        <label>ОГРН</label>{{ Form::text('ogrn') }}
    </div>
     <div class="form-element">
        <label>ИНН</label>{{ Form::text('inn') }}
    </div>
    <div class="form-element">
        <label>КПП</label>{{ Form::text('kpp') }}
    </div>
    <div class="form-element">
        <label>Тип счёта</label>{{ Form::select('account_type',AccountTypes::lists('title','id'),$profile->account_type_id,array('class'=>'select')) }}
    </div>
    <div class="form-element">
        <label>Расчетный счет</label>{{ Form::text('account_number') }}
    </div>
    <div class="form-element">
        <label>Корреспондентский счет</label>{{ Form::text('account_kor_number') }}
    </div>
    <div class="form-element">
        <label>Наименование банка</label>{{ Form::text('bank') }}
    </div>
    <div class="form-element">
        <label>БИК</label>{{ Form::text('bik') }}
    </div>
    <div class="form-element">
        <label>Телефон</label>{{ Form::text('phone',NULL,array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Контактное лицо</label>{{ Form::text('name') }}
    </div>
    <div class="form-element">
        <label>Скидка, %</label>{{ Form::text('discount') }}
    </div>
    <div class="form-element">
        <label>Статус аккаунта</label>{{ Form::select('moderator_approve',array('Не подтвержден','Подтвержден'),$profile->moderator_approve,array('class'=>'select')) }}
    </div>
    <div class="form-element">
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
        </button>
        <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
    </div>
{{ Form::close() }}