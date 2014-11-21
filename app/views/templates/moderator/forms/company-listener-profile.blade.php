{{ Form::model($profile,array('url'=>URL::route('moderator-listener-profile-update',$profile->id), 'class'=>'registration-form', 'id'=>'company-profile-listener-form', 'method'=>'PATCH')) }}
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
        <label>Ф.И.О.</label>{{ Form::text('fio') }}
    </div>
    <div class="form-element">
        <label>Ф.И.О. в дат. падеже</label>{{ Form::text('fio_dat') }}
    </div>
    <div class="form-element">
        <label>Должность</label>{{ Form::text('position') }}
    </div>
    <div class="form-element">
        <label>Адрес</label>{{ Form::text('postaddress') }}
    </div>
    <div class="form-element">
        <label>Телефон</label>{{ Form::text('phone',NULL,array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Образование</label>{{ Form::text('education') }}
    </div>
    <div class="form-element">
        <label>Номер и дата выдачи документа об образовании</label>{{ Form::text('education_document_data') }}
    </div>
    <div class="form-element">
        <label>Наименование специальности</label>{{ Form::text('specialty') }}
    </div>
    <div class="form-element">
        <label>Наименование учебного заведения</label>{{ Form::text('educational_institution') }}
    </div>
    <div class="form-element">
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
        </button>
        <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
    </div>
{{ Form::close() }}