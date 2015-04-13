{{ Form::model($profile,array('url'=>URL::route('organization-profile-update'), 'class'=>'registration-form listener-add-form', 'id'=>'profile-company-form', 'method'=>'PATCH')) }}
    {{ Form::hidden('account_type',AccountTypes::pluck('id')) }}
    {{ Form::hidden('fio_manager_rod') }}
    {{ Form::hidden('manager_rod') }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Полное наименование организации</label>{{ Form::text('title') }}
    </div>
    <div class="form-element">
        <label>ФИО подписанта договора</label>{{ Form::text('fio_manager') }}
    </div>
    <div class="form-element">
        <label>Должность подписанта договора</label>{{ Form::text('manager') }}
    </div>
    <div class="form-element">
        <label>Документ, на основании которого действует подписант</label>{{ Form::text('statutory') }}
    </div>
    <div class="form-element">
        <label>Адрес регистрации</label>{{ Form::text('uraddress') }}
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
        <label>Телефон контактного лица</label>{{ Form::text('phone',NULL,array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Ф.И.О. контактного лица</label>{{ Form::text('name') }}
    </div>
    <div class="form-element row no-gutter margin-top-40">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
        </div>
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
            </button>
        </div>
    </div>
{{ Form::close() }}