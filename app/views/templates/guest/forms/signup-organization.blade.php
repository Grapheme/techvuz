{{ Form::open(array('url'=>URL::route('signup-ul'), 'class'=>'registration-form', 'id'=>'signup-ul-form', 'method'=>'post')) }}
    {{ Form::hidden('group_id',@Group::where('name','organization')->pluck('id')) }}
    {{ Form::hidden('account_type',AccountTypes::pluck('id')) }}
    {{ Form::hidden('fio_manager_rod','Не указано') }}
    {{ Form::hidden('manager_rod','Не указано') }}
    <div class="reg-form-alert">
        <img class="vertical-text-bot" src="{{ asset('theme/img/triangle.png') }}"> Все поля являются обязательными для заполнения
    </div>
    <fieldset>
        <div class="form-element">
            <label>Полное наименование организации</label>{{ Form::text('title', '',array('placeholder'=>'Общество с ограниченной ответственность «Строитель»')) }}
        </div>
        <div class="form-element">
            <label>ФИО подписанта договора</label>{{ Form::text('fio_manager', '',array('placeholder'=>'Иванов Иван Иванович')) }}
        </div>
        <div class="form-element">
            <label>Должность подписанта договора</label>{{ Form::text('manager', '',array('placeholder'=>'Директор, менеджер, заместитель генерального директора')) }}
        </div>
        <div class="form-element">
            <label>Подписант действует на основании</label>{{ Form::text('statutory', '',array('placeholder'=>'Устав, доверенность №… от …')) }}
        </div>
         <div class="form-element reg-adress-consilience">
            <label>Адрес регистрации</label>{{ Form::text('uraddress', '',array('placeholder'=>'123022, г. Москва, ул. Красная Пресня, 46')) }}
        </div>
        <div class="form-element post-adress-consilience">
            <label>Почтовый адрес</label>{{ Form::text('postaddress', '',array('placeholder'=>'123022, г. Москва, ул. Красная Пресня, 46')) }}
            <p><input type="checkbox"  id="adress-check"> Совпадает с юридическим</p>
        </div>
        <div class="form-element">
            <label>ОГРН </label>{{ Form::text('ogrn', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>ИНН</label>{{ Form::text('inn', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>КПП (при наличии)</label>{{ Form::text('kpp', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Расчетный счет</label>{{ Form::text('account_number', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Корреспондентский счет</label>{{ Form::text('account_kor_number', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Наименование банка</label>{{ Form::text('bank', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>БИК</label>{{ Form::text('bik', '',array('placeholder'=>'')) }}
        </div>
    </fieldset>
    <fieldset>
        <header class="margin-bottom-20">Контактные данные</header>
        <div class="form-element">
            <label>E-mail</label>{{ Form::text('email', '',array('placeholder'=>'')) }}
        </div>
        <div class="form-element">
            <label>Ф.И.О.</label>{{ Form::text('name', '',array('placeholder'=>'Иванов Иван Иванович')) }}
        </div>
        <div class="form-element">
            <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
        </div>
    </fieldset>
    <fieldset>
        <div class="form-element check-element">
            <label>Даю согласие на обработку персональных данных</label>{{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent-ul')) }}
        </div>
        <div class="form-element">
            <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
            </button>
        </div>
    </fieldset>
{{ Form::close() }}