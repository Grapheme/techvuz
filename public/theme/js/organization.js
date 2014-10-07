var validation_signup_listener = {
    group_id: { required: true },
    organization_id: { required: true },
    fio: { required: true },
    position: { required: true },
    email: { required: true, email: true },
    postaddress: { required: true },
    phone: { required: true },
    education: { required: true },
    place_work: { required: true },
    year_study: { required: true },
    specialty: { required: true }
};
var validation_signup_messages_listener = {
    group_id: { required: 'Укажите группу' },
    organization_id: { required: 'Укажите организацию' },
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    email: { required: 'Укажите контактный E-mail','email': 'Неверно указан формат данных' },
    postaddress: { required: 'Укажите почтовый адрес' },
    phone: { required: 'Укажите контактный номер' },
    education: { required: 'Укажите образование' },
    place_work: { required: 'Укажите образование' },
    year_study: { required: 'Укажите год обучения' },
    specialty: { required: 'Укажите специальность' }
};

var validation_profile_listener = {
    fio: { required: true },
    position: { required: true },
    postaddress: { required: true },
    phone: { required: true },
    education: { required: true },
    place_work: { required: true },
    year_study: { required: true },
    specialty: { required: true }
};
var validation_profile_messages_listener = {
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    postaddress: { required: 'Укажите почтовый адрес' },
    phone: { required: 'Укажите контактный номер' },
    education: { required: 'Укажите образование' },
    place_work: { required: 'Укажите образование' },
    year_study: { required: 'Укажите год обучения' },
    specialty: { required: 'Укажите специальность' }
};

var validation_profile_ul = {
    group_id: { required: true },
    title: { required: true },
    fio_manager: { required: true },
    manager: { required: true },
    statutory: { required: true },
    inn: { required: true },
    kpp: { required: true },
    postaddress: { required: true },
    account_number: { required: true },
    bank: { required: true },
    bik: { required: true },
    email: { required: true, email: true },
    name: { required: true },
    phone: { required: true },
    consent: { required: true }
};
var validation_profile_messages_ul = {
    group_id: { required: 'Укажите группу' },
    title: { required: 'Укажите название' },
    fio_manager: { required: 'Укажите фамилию, имя и отчество руководителя' },
    manager: { required: 'Укажите должность' },
    statutory: { required: 'Укажите уставной документ' },
    inn: { required: 'Укажите ИНН' },
    kpp: { required: 'Укажите КПП' },
    postaddress: { required: 'Укажите почтовый адрес' },
    account_number: { required: 'Укажите номер счета' },
    bank: { required: 'Укажите наименование банка' },
    bik: { required: 'Укажите БИК' },
    email: { required: 'Укажите контактный E-mail','email': 'Неверно указан формат данных' },
    name: { required: 'Укажите контактное лицо' },
    phone: { required: 'Укажите контактный номер' },
    consent: { required: '' }
};

function scrollToError(elem) {
    $('html, body').animate({
        scrollTop: elem.offset().top
    }, 200);
}

function organizationFormValidation() {

    var signupListener = $("#signup-listener-form").validate({
        rules: validation_signup_listener ? validation_signup_listener : {},
        messages: validation_signup_messages_listener ? validation_signup_messages_listener : {},
        errorPlacement : function(error, element){error.insertAfter(element.parent());},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
                $("#error").remove();
                $(form).find('.btn-form-submit').elementDisabled(true);
            },
                options.success = function(response, status, xhr, jqForm){
                    $(form).find('.btn-form-submit').elementDisabled(false);
                    if(response.status){
                        if(response.redirect !== false){
                            BASIC.RedirectTO(response.redirect);
                        }else{
                            $(form).replaceWith(response.responseText);
                        }
                    }else{
                        $(form).find('.btn-form-submit').parent().parent().parent().before("<p class='error' id='error'>"+response.responseText+"</p>");
                        
                        scrollToError( $('#error') );
                    }
                }
            options.error = function(xhr, textStatus, errorThrown){
                if (typeof(xhr.responseJSON) != 'undefined') {
                    var err_type = xhr.responseJSON.error.type;
                    var err_file = xhr.responseJSON.error.file;
                    var err_line = xhr.responseJSON.error.line;
                    var err_message = xhr.responseJSON.error.message;
                    var msg_title = err_type;
                    var msg_body = err_file + ":" + err_line + "<hr/>" + err_message;
                } else {
                    var msg_title = textStatus;
                    var msg_body = xhr.responseText;
                }
                $(form).find('.btn-form-submit').elementDisabled(false);
            }
            $(form).ajaxSubmit(options);
        }
    });

    var profileListener = $("#profile-listener-form").validate({
        rules: validation_profile_listener ? validation_profile_listener : {},
        messages: validation_profile_messages_listener ? validation_profile_messages_listener : {},
        errorPlacement : function(error, element){error.insertAfter(element.parent());},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
                $("#error").remove();
                $(form).find('.btn-form-submit').elementDisabled(true);
            },
                options.success = function(response, status, xhr, jqForm){
                    $(form).find('.btn-form-submit').elementDisabled(false);
                    if(response.status){
                        if(response.redirect !== false){
                            $(form).find('.btn-form-submit').html(response.responseText);
                            BASIC.RedirectTO(response.redirect);
                        }else{
                            $(form).replaceWith(response.responseText);
                        }
                    }else{
                        $(form).find('.btn-form-submit').before("<p id='error'>"+response.responseText+"</p>");
                    }
                }
            options.error = function(xhr, textStatus, errorThrown){
                if (typeof(xhr.responseJSON) != 'undefined') {
                    var err_type = xhr.responseJSON.error.type;
                    var err_file = xhr.responseJSON.error.file;
                    var err_line = xhr.responseJSON.error.line;
                    var err_message = xhr.responseJSON.error.message;
                    var msg_title = err_type;
                    var msg_body = err_file + ":" + err_line + "<hr/>" + err_message;
                } else {
                    var msg_title = textStatus;
                    var msg_body = xhr.responseText;
                }
                $(form).find('.btn-form-submit').elementDisabled(false);
            }
            $(form).ajaxSubmit(options);
        }
    });

    var profileCompany = $("#profile-company-form").validate({
        rules: validation_profile_ul ? validation_profile_ul : {},
        messages: validation_profile_messages_ul ? validation_profile_messages_ul : {},
        errorPlacement : function(error, element){error.insertAfter(element.parent());},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
                $("#error").remove();
                $(form).find('.btn-form-submit').elementDisabled(true);
            },
                options.success = function(response, status, xhr, jqForm){
                    $(form).find('.btn-form-submit').elementDisabled(false);
                    if(response.status){
                        if(response.redirect !== false){
                            $(form).find('.btn-form-submit').html(response.responseText);
                            BASIC.RedirectTO(response.redirect);
                        }else{
                            $(form).replaceWith(response.responseText);
                        }
                    }else{
                        $(form).find('.btn-form-submit').before("<p id='error'>"+response.responseText+"</p>");
                    }
                }
            options.error = function(xhr, textStatus, errorThrown){
                if (typeof(xhr.responseJSON) != 'undefined') {
                    var err_type = xhr.responseJSON.error.type;
                    var err_file = xhr.responseJSON.error.file;
                    var err_line = xhr.responseJSON.error.line;
                    var err_message = xhr.responseJSON.error.message;
                    var msg_title = err_type;
                    var msg_body = err_file + ":" + err_line + "<hr/>" + err_message;
                } else {
                    var msg_title = textStatus;
                    var msg_body = xhr.responseText;
                }
                $(form).find('.btn-form-submit').elementDisabled(false);
            }
            $(form).ajaxSubmit(options);
        }
    });
}
