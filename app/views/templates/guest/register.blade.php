@extends(Helper::layout())
@section('style')

@stop
@section('content')
<ul class="nav nav-tabs bordered" id="myTab1">
    <li class="active">
        <a data-toggle="tab" href="#s1">Юридическое лицо</a>
    </li>
    <li class="">
        <a data-toggle="tab" href="#s2">Физическое лицо</a>
    </li>
</ul>
{{ Form::open(array('url'=>URL::route('signup'), 'role'=>'form', 'class'=>'smart-form', 'id'=>'signup-form', 'method'=>'post')) }}
    <div class="row margin-top-10">
        <section class="col col-6">
            <div class="well">
                <div class="tab-content padding-10" id="myTabContent1">
                    <div id="s1" class="tab-pane fade in active">
                        {{ Form::hidden('group_id',4) }}
                        <fieldset>
                            <section>
                                <label class="label">Наименование</label>
                                <label class="input">
                                    {{ Form::text('organization', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Ф.И.О. руководителя</label>
                                <label class="input">
                                    {{ Form::text('fio_manager', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Должность</label>
                                <label class="input">
                                    {{ Form::text('manager', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Уставной документ</label>
                                <label class="input">
                                    {{ Form::text('statutory', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">ИНН</label>
                                <label class="input">
                                    {{ Form::text('inn', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">КПП</label>
                                <label class="input">
                                    {{ Form::text('kpp', '') }}
                                </label>
                            </section>
                        </fieldset>
                        <fieldset>
                            <section>
                                <label class="label">Тип счёта</label>
                                <label class="select">
                                    {{ Form::select('account_type',AccountTypes::lists('title','id')) }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Номер счёта</label>
                                <label class="input">
                                    {{ Form::text('account_number', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Наименование банка</label>
                                <label class="textarea">
                                    {{ Form::textarea('bank', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Номер кор. счёта</label>
                                <label class="input">
                                    {{ Form::text('account_kor_number', '') }}
                                </label>
                            </section>
                        </fieldset>
                        <fieldset>
                            <section>
                                <label class="label">Юридический адрес</label>
                                <label class="textarea">
                                    {{ Form::textarea('ur_address', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Почтовый адрес</label>
                                <label class="textarea">
                                    {{ Form::textarea('post_address', '') }}
                                </label>
                            </section>
                        </fieldset>
                        <fieldset>
                            <header>Контактные данные</header>
                            <section>
                                <label class="label">E-mail</label>
                                <label class="input">
                                    {{ Form::text('person_email', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Контактное лицо</label>
                                <label class="input">
                                    {{ Form::text('person_name', '') }}
                                </label>
                            </section>
                            <section>
                                <label class="label">Номер конт.телефона</label>
                                <label class="input">
                                    {{ Form::text('person_phone', '') }}
                                </label>
                            </section>
                        </fieldset>
                        <fieldset>
                            <section>
                                <label class="checkbox">
                                {{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent')) }}
                                <input type="checkbox" id="terms" name="terms">
                                <i></i>I agree with the Terms and Conditions</label>
                            </section>
                        </fieldset>
                    </div>
                    <div id="s2" class="tab-pane fade">
                        {{ Form::hidden('group_id',6) }}
                        <fieldset>
                            <section>
                                <label class="label">Ф.И.О.</label>
                                <label class="input">
                                    {{ Form::text('fio', '') }}
                                </label>
                            </section>
                        </fieldset>
                    </div>
                    <footer>
                        <button type="submit" autocomplete="off" class="btn btn-success no-margin regular-10 uppercase btn-form-submit">
                            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Регистрация</span>
                        </button>
                    </footer>
                </div>
            </div>
        </section>
    </div>
{{ Form::close() }}
@stop
@section('overlays')
@stop
@section('scripts')
<script>
var essence = 'signup';
var essence_name = 'регистрация';
var validation_rules = {
    group_id: { required: true },
    organization: { required: true },
    fio_manager: { required: true },
    manager: { required: true },
    statutory: { required: true },
    inn: { required: true },
    kpp: { required: true },
    account_number: { required: true },
    bank: { required: true },
    account_kor_number: { required: true },
    bik: { required: true },
    ur_address: { required: true },
    post_address: { required: true },
    person_email: { required: true, email: true },
    person_name: { required: true },
    person_phone: { required: true },

    fio: { required: true },
};
var validation_messages = {
    group_id: { required: 'Укажите группу' },
    organization: { required: 'Укажите название' },
    fio_manager: { required: 'Укажите фамилию, имя и отчество руководителя' },
    manager: { required: 'Укажите должность' },
    statutory: { required: 'Укажите уставной документ' },
    inn: { required: 'Укажите ИНН' },
    kpp: { required: 'Укажите КПП' },
    account_number: { required: 'Укажите номер счета' },
    bank: { required: 'Укажите наименование банка' },
    account_kor_number: { required: 'Укажите номер кор. счёта' },
    bik: { required: 'Укажите БИК' },
    ur_address: { required: 'Укажите юридический адрес' },
    post_address: { required: 'Укажите почтовый адрес' },
    person_email: { required: 'Укажите контактный E-mail' },
    person_name: { required: 'Укажите контактное лицо' },
    person_phone: { required: 'Укажите контактный номер' },

    fio: { required: 'Укажите фамилию, имя и отчество' },
};
</script>
{{ HTML::script('theme/js/plugins.js') }}
<script type="text/javascript">
    if(typeof pageSetUp === 'function'){pageSetUp();}
    if(typeof runFormValidation === 'function'){
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}", runFormValidation);
    }else{
        loadScript("{{ asset('js/vendor/jquery-form.min.js') }}");
    }
</script>
@stop