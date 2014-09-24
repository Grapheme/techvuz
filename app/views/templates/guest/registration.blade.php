@extends(Helper::layout())
@section('style')

@stop
@section('content')
<main class="registration">
    {{ $page->block('top_h2') }}
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <div class="tabs">
        <ul>
            <li><a href="#tabs-1">Юридическое лицо</a></li>
            <li><a href="#tabs-2">Физическое лицо</a></li>
        </ul>
        <div id="tabs-1">
            {{ Form::open(array('url'=>URL::route('signup-ul'), 'role'=>'form', 'class'=>'registration-form', 'id'=>'signup-ul-form', 'method'=>'post')) }}
                {{ Form::hidden('group_id',@Group::where('name','organization')->first()->id) }}
                <div class="reg-form-alert">
                    Все поля являются обязательными для заполнения!
                </div>
                <fieldset>
                    <div class="form-element">
                        <label>Наименование учреждения</label>{{ Form::text('organization', '') }}
                    </div>
                    <div class="form-element">
                        <label>Ф.И.О. ответственного лица</label>{{ Form::text('fio_manager', '') }}
                    </div>
                    <div class="form-element">
                        <label>Должность</label>{{ Form::text('manager', '') }}
                    </div>
                    <div class="form-element">
                        <label>Уставной документ</label>{{ Form::text('statutory', '') }}
                    </div>
                    <div class="form-element">
                        <label>ИНН</label>{{ Form::text('inn', '') }}
                    </div>
                    <div class="form-element">
                        <label>КПП</label>{{ Form::text('kpp', '') }}
                    </div>
                    <div class="form-element">
                        <label>Почтовый адрес</label>{{ Form::text('postaddress', '') }}
                    </div>
                    <div class="form-element">
                        <label>Тип счёта</label>{{ Form::select('account_type',AccountTypes::lists('title','id'),0,array('class'=>'select')) }}
                    </div>
                    <div class="form-element">
                        <label>Номер счета</label>{{ Form::text('account_number', '') }}
                    </div>
                    <div class="form-element">
                        <label>Наименование банка</label>{{ Form::text('bank', '') }}
                    </div>
                    <div class="form-element">
                        <label>БИК</label>{{ Form::text('bik', '') }}
                    </div>
                </fieldset>
                <fieldset>
                    <header>Контактные данные</header>
                    <div class="form-element">
                        <label>E-mail</label>{{ Form::text('email', '',array('class'=>'email')) }}
                    </div>
                    <div class="form-element">
                        <label>Контактное лицо</label>{{ Form::text('name', '') }}
                    </div>
                    <div class="form-element">
                        <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
                    </div>
                </fieldset>
                <fieldset>
                    <div class="form-element">
                        <label>Даю согласие на обработку персональных данных</label>{{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent-ul')) }}
                    </div>
                    <div class="form-element">
                        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
                        </button>
                    </div>
                </fieldset>
            {{ Form::close() }}
        </div>
        <div id="tabs-2">
            {{ Form::open(array('url'=>URL::route('signup-fl'), 'role'=>'form', 'class'=>'registration-form', 'id'=>'signup-fl-form', 'method'=>'post')) }}
                {{ Form::hidden('group_id',@Group::where('name','individual')->first()->id) }}
                <div class="reg-form-alert">
                    Все поля являются обязательными для заполнения!
                </div>
                <fieldset>
                    <div class="form-element">
                        <label>Ф.И.О.</label>{{ Form::text('fio', '') }}
                    </div>
                    <div class="form-element">
                        <label>Должность</label>{{ Form::text('position', '') }}
                    </div>
                    <div class="form-element">
                        <label>ИНН</label>{{ Form::text('inn', '') }}
                    </div>
                    <div class="form-element">
                        <label>E-mail</label>{{ Form::text('email', '',array('class'=>'email')) }}
                    </div>
                    <div class="form-element">
                        <label>Номер телефона</label>{{ Form::text('phone', '',array('class'=>'phone')) }}
                    </div>
                    <div class="form-element">
                        <label>Почтовый адрес</label>{{ Form::text('postaddress', '') }}
                    </div>
                </fieldset>
                <fieldset>
                    <div class="form-element">
                        <label>Даю согласие на обработку персональных данных</label>{{ Form::checkbox('consent',1,FALSE,array('autocomplete'=>'off','id'=>'input-consent-fz')) }}
                    </div>
                    <div class="form-element">
                        <button type="submit" autocomplete="off" class="btn btn--bordered btn--blue btn-form-submit">
                            <i class="fa fa-spinner fa-spin hidden"></i> <span class="btn-response-text">Готово</span>
                        </button>
                    </div>
                </fieldset>
            {{ Form::close() }}
        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
{{ HTML::script('js/vendor/jquery-form.min.js') }}
{{ HTML::script('js/vendor/jquery.validate.min.js') }}
{{ HTML::script('js/system/main.js') }}
{{ HTML::script('theme/scripts/registration.js') }}
<script type="text/javascript">runFormValidation();</script>
{{ HTML::script('js/vendor/jquery.mask.js') }}
<script>
    $(document).ready(function(){
        $(".phone").inputmask("mask", {"mask": "[+7] (999) 999 99 99","placeholder": "X"});
        $(".email").inputmask({
                mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
                greedy: false,
                onBeforePaste: function (pastedValue, opts) {
                    pastedValue = pastedValue.toLowerCase();
                    return pastedValue.replace("mailto:", "");
                },
                definitions: {
                    '*': {
                        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
                        cardinality: 1,
                        casing: "lower"
                    }
                }
        });
    });
</script>
@stop