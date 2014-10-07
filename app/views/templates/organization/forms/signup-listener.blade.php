{{ Form::open(array('url'=>URL::route('signup-listener'), 'class'=>'registration-form listener-add-form', 'id'=>'signup-listener-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','listener')->pluck('id')) }}
    {{ Form::hidden('organization_id',Auth::user()->id) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Ф.И.О.</label>{{ Form::text('fio', '') }}
    </div>
    <div class="form-element">
        <label>Должность</label>{{ Form::text('position', '') }}
    </div>
    <div class="form-element">
        <label>Email</label>{{ Form::email('email', '') }}
    </div>
    <div class="form-element">
        <label>Адрес</label>{{ Form::text('postaddress', '') }}
    </div>
    <div class="form-element">
        <label>Телефон</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Образование</label>{{ Form::text('education', '') }}
    </div>
    <div class="form-element">
        <label>Место работы</label>{{ Form::text('place_work') }}
    </div>
    <div class="form-element">
        <label>Год обучения</label>{{ Form::text('year_study', '',array('class'=>'year')) }}
    </div>
    <div class="form-element">
        <label>Специальность</label>{{ Form::text('specialty') }}
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