{{ Form::open(array('url'=>URL::route('signup-listener'), 'class'=>'registration-form listener-add-form', 'id'=>'signup-listener-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','listener')->pluck('id')) }}
    {{ Form::hidden('organization_id',Auth::user()->id) }}
    <div class="reg-form-alert">
        <img class="vertical-text-bot" src="{{ asset('theme/img/triangle.png') }}"> Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Ф.И.О.</label>{{ Form::text('fio', '',array('placeholder'=>'Иванов Иван Иванович')) }}
    </div>
    <div class="form-element">
        <label>ФИО в дат. падеже</label>{{ Form::text('fio_dat', '',array('placeholder'=>'Иванову Ивану Ивановичу')) }}
    </div>
    <div class="form-element">
        <label>Должность</label>{{ Form::text('position', '',array('placeholder'=>'Начальник ПТО, ведущий инженер')) }}
    </div>
     <div class="form-element">
        <label>Адрес</label>{{ Form::text('postaddress', '',array('placeholder'=>'121354, г. Москва, ул. Кутузова, 57, кв. 9')) }}
    </div>
    <div class="form-element">
        <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Email</label>{{ Form::email('email', '',array('placeholder'=>'')) }}
    </div>
    <div class="form-element">
        <label>Образование</label>{{ Form::text('education', '',array('placeholder'=>'Высшее, среднее профессиональное')) }}
    </div>
    <div class="form-element">
        <label>Номер и дата выдачи документа об образовании</label>{{ Form::text('education_document_data','',array('placeholder'=>'РВ №112233 от 17.06.2003')) }}
    </div>
    <div class="form-element">
        <label>Наименование специальности</label>{{ Form::text('specialty','',array('placeholder'=>'Промышленное и гражданское строительство')) }}
    </div>
    <div class="form-element">
        <label>Наименование учебного заведения</label>{{ Form::text('educational_institution', '',array('placeholder'=>'Указать как в дипломе')) }}
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