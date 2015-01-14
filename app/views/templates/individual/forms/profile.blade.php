{{ Form::model($profile,array('url'=>URL::route('individual-profile-update'), 'class'=>'registration-form listener-add-form', 'id'=>'profile-individual-form', 'method'=>'PATCH')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Ф.И.О.</label>{{ Form::text('fio') }}
    </div>
    <div class="form-element">
        <label>Ф.И.О. в род. падеже</label>{{ Form::text('fio_rod') }}
    </div>
    <div class="form-element">
        <label>Серия паспорта</label>{{ Form::text('passport_seria') }}
    </div>
    <div class="form-element">
        <label>Номер паспорта</label>{{ Form::text('passport_number') }}
    </div>
    <div class="form-element">
        <label>Кем выдан</label>{{ Form::text('passport_data') }}
    </div>
    <div class="form-element">
        <label>Дата выдачи</label>{{ Form::text('passport_date') }}
    </div>
    <div class="form-element">
        <label>Код подразделения</label>{{ Form::text('code') }}
    </div>
    <div class="form-element">
        <label>Зарегистрирован по адресу</label>{{ Form::text('postaddress') }}
    </div>
    <div class="form-element">
        <label>Номер телефона</label>{{ Form::text('phone') }}
    </div>

    <div class="form-element">
        <label>Должность</label>{{ Form::text('position') }}
    </div>
    <div class="form-element">
        <label>Образование</label>{{ Form::text('education') }}
    </div>
    <div class="form-element">
        <label>Номер и дата выдачи документа об образовании</label>{{ Form::text('document_education') }}
    </div>
    <div class="form-element">
        <label>Наименование специальности</label>{{ Form::text('specialty') }}
    </div>
    <div class="form-element">
        <label>Наименование учебного заведения</label>{{ Form::text('educational_institution') }}
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