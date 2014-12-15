<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Ф.И.О.</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->fio }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Ф.И.О. в род. падеже</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->fio_rod }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Серия паспорта</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->passport_seria }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Номер паспорта</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->passport_number }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Кем выдан</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->passport_data }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Дата выдачи</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->passport_date }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Код подразделения</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->code }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Зарегистрирован по адресу</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->postaddress }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Номер телефона</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->phone }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">E-mail</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->email }}
    </div>
</div>


<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Должность</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->position }}
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Образование</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->education }}
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Номер и дата выдачи документа об образовании</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->document_education }}
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Наименование специальности</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->specialty }}
    </div>
</div>

<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Наименование учебного заведения</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->educational_institution }}
    </div>
</div>