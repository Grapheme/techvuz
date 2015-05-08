{{ Form::model($profile,array('url'=>URL::route('moderator-listener-profile-update',$profile->id), 'class'=>'registration-form', 'id'=>'individual-profile-listener-form', 'method'=>'PATCH')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Email</label>{{ Form::text('email') }}
    </div>
    <div class="form-element">
        <label>Статус E-mail адреса</label>{{ Form::select('active',array('Не активный','Активный','Не активирован'),NULL,array('class'=>'select')) }}
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

    <div class="form-element">
        <label>Скидка, %</label>{{ Form::text('discount') }}
    </div>
    <div class="form-element">
        <label>Статус аккаунта</label>{{ Form::select('moderator_approve',array('Модератором не подтвержден','Модератором подтвержден'),$profile->moderator_approve,array('class'=>'select')) }}
    </div>
    <div class="form-element">
        <label>Статистика</label>{{ Form::select('statistic',array('Не учитывать в статистике','Учитывать в статистике'),$profile->statistic,array('class'=>'select')) }}
    </div>
    <div class="form-element">
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
        </button>
        <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
    </div>
{{ Form::close() }}