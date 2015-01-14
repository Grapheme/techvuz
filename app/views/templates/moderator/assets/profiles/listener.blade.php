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
        <span class="font-sm">Ф.И.О. в дат. падеже</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->fio_dat }}
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
        <span class="font-sm">Адрес</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->postaddress }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Email</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->email }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Телефон</span>
    </div>
    <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
        {{ $profile->phone }}
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
        {{ $profile->education_document_data }}
    </div>
</div>
<div class="row margin-bottom-10">
    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
        <span class="font-sm">Специальность</span>
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