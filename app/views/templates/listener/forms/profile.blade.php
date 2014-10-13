{{ Form::model($profile,array('url'=>URL::route('listener-profile-update'), 'class'=>'registration-form', 'id'=>'profile-listener-form', 'method'=>'PATCH')) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Ф.И.О.</label>{{ Form::text('fio') }}
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
        <label>Место работы</label>{{ Form::text('place_work') }}
    </div>
    <div class="form-element">
        <label>Год обучения</label>{{ Form::text('year_study',NULL,array('class'=>'year')) }}
    </div>
    <div class="form-element">
        <label>Специальность</label>{{ Form::text('specialty') }}
    </div>
    <div class="form-element">
        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
        </button>
        <a class="btn btn--bordered btn--blue" href="{{ URL::previous() }}">Вернуться назад</a>
    </div>
{{ Form::close() }}