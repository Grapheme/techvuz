{{ Form::model($profile,array('url'=>URL::route('listener-profile-update'), 'class'=>'registration-form listener-add-form', 'id'=>'profile-listener-form', 'method'=>'PATCH')) }}
    {{ Form::hidden('approved',1) }}
    <div class="reg-form-alert">
        Все поля являются обязательными для заполнения!
    </div>
    <div class="form-element">
        <label>Ф.И.О.</label>{{ Form::text('fio',NULL,array('placeholder'=>'Иванов Иван Иванович')) }}
    </div>
    <div class="form-element">
        <label>ФИО в дат. падеже</label>{{ Form::text('fio_dat',NULL,array('placeholder'=>'Иванову Ивану Ивановичу')) }}
    </div>
    <div class="form-element">
        <label>Должность</label>{{ Form::text('position',NULL,array('placeholder'=>'Начальник ПТО, ведущий инженер')) }}
    </div>
    <div class="form-element">
        <label>Адрес</label>{{ Form::text('postaddress',NULL,array('placeholder'=>'121354, г. Москва, ул. Кутузова, 57, кв. 9')) }}
    </div>
    <div class="form-element">
        <label>Номер телефона</label>{{ Form::text('phone',NULL,array('class'=>'phone')) }}
    </div>
    <div class="form-element">
        <label>Образование</label>{{ Form::text('education',NULL,array('placeholder'=>'Высшее, среднее профессиональное')) }}
    </div>
    <div class="form-element">
        <label>Номер и дата выдачи документа об образовании</label>{{ Form::text('education_document_data',NULL,array('placeholder'=>'РВ №112233 от 17.06.2003')) }}
    </div>
    <div class="form-element">
        <label>Наименование специальности</label>{{ Form::text('specialty',NULL,array('placeholder'=>'Промышленное и гражданское строительство')) }}
    </div>
    <div class="form-element">
        <label>Наименование учебного заведения</label>{{ Form::text('educational_institution',NULL,array('placeholder'=>'Указать как в дипломе')) }}
    </div>

    <div class="row margin-bottom-10">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: normal; font-size: 12px">
            Нажимая на кнопку «Готово», Вы даете согласие на обработку <a href="{{asset('files/agreement.pdf')}}" target="_blank" class="icon--blue">персональных данных</a>.
        </div>
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