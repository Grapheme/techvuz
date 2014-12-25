var validation_signin = {
    login: { required: true, email: true },
    password: { required: true }
};
var validation_signin_messages = {
    login: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    password: { required: 'Укажите пароль' }
};
var validation_restore_pass = {
    email: { required: true, email: true }
};
var validation_restore_pass_messages = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' }
};

var validation_signup_ul = {
    group_id: { required: true },
    title: { required: true },
    fio_manager: { required: true },
    fio_manager_rod: { required: true },
    manager: { required: true },
    statutory: { required: true },
    ogrn: {
        required: true,
        minlength: 13,
        maxlength: 15
    },
    inn: {
        required: true,
        minlength: 10,
        maxlength: 12
    },
    kpp: {},
    uraddress: { required: true },
    account_number: {
        required: true,
        minlength: 20,
        maxlength: 25
    },
    account_kor_number: { required: true },
    bank: { required: true },
    bik: { 
        required: true,
        minlength: 9,
        maxlength: 9
    },
    email: {
        required: true,
        email: true,
        remote: {
           url: "/check-email",
           type: "post",
           data: {
               email: function() {
                   return $("#signup-ul-form input[name='email']").val();
               }
           },
           dataType: "json",
           dataFilter: function (data) {
               if (data.email == true) {
                   return true;
               }
           }
        }
    },
    name: { required: true },
    phone: { required: true },
    consent: { required: true }
};
var validation_signup_messages_ul = {
    group_id: { required: 'Укажите группу' },
    title: { required: 'Укажите название' },
    fio_manager: { required: 'Укажите фамилию, имя и отчество подписанта' },
    fio_manager_rod: { required: 'Укажите фамилию, имя и отчество подписанта в род. падеже' },
    manager: { required: 'Укажите должность' },
    statutory: { required: 'Укажите уставной документ' },
    ogrn: { 
        required: 'Укажите ОГРН',
        minlength: 'Укажите ОГРН от 13 до 15 знаков!',
        maxlength: 'Укажите ОГРН от 13 до 15 знаков!'
    },
    inn: {
        required: 'Укажите ИНН',
        minlength: 'Укажите ИНН от 10 до 12 знаков!',
        maxlength: 'Укажите ИНН от 10 до 12 знаков!'
    },
    kpp: { required: 'Укажите КПП' },
    uraddress: { required: 'Укажите юридический адрес' },
    account_number: { 
        required: 'Укажите расчетный счет',
        minlength: 'Укажите расчетный счет от 20 до 25 знаков!',
        maxlength: 'Укажите расчетный счет от 20 до 25 знаков!'
    },
    account_kor_number: { required: 'Укажите корреспондентский счет' },
    bank: { required: 'Укажите наименование банка' },
    bik: { 
        required: 'Укажите БИК',
        minlength: 'Укажите БИК 9 знаков!',
        maxlength: 'Укажите БИК 9 знаков!'
    },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail','remote': 'Email уже зарегистрирован' },
    name: { required: 'Укажите контактное лицо' },
    phone: { required: 'Укажите контактный номер' },
    consent: { required: 'Для регистрации необходимо дать согласие на обработку' }
};
var validation_signup_fl = {
    group_id: { required: true },
    fio: { required: true },
    fio_rod: { required: true },
    passport_seria: { required: true },
    passport_number: { required: true },
    passport_data: { required: true },
    passport_date: { required: true },
    code: { required: true },
    postaddress: { required: true },
    email: {
        required: true,
        email: true
        //remote: {
        //    url: "/check-email",
        //    type: "post",
        //    data: {
        //        email: function() {
        //            return $("#signup-fl-form input[name='email']").val();
        //        }
        //    },
        //    dataType: "json",
        //    success: function (data) {
        //        if (data.email == true) {
        //            message: {
        //                email: data.message;
        //            }
        //            return false;
        //        }
        //    }
        //}
    },
    phone: { required: true },
    consent: { required: true }
};
var validation_signup_messages_fl = {
    group_id: { required: 'Укажите группу' },
    fio: { required: 'Укажите Ф.И.О.' },
    fio_rod: { required: 'Укажите Ф.И.О. в род. падеже' },
    passport_seria: { required: 'Укажите серию паспорта' },
    passport_number: { required: 'Укажите Номер паспорта' },
    passport_data: { required: 'Укажите кем выдан паспорт' },
    passport_date: { required: 'Укажите дату выдачи паспорта' },
    code: { required: 'Укажите rод подразделения' },
    postaddress: { required: 'Укажите почтовый адрес' },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail','remote': 'Email уже зарегистрирован' },
    phone: { required: 'Укажите контактный номер' },
    consent: { required: '' }
};

var validation_reset_password = {
    token: { required: true },
    email: { required: true, email: true },
    password: { required: true,minlength: 6 },
    password_confirmation: { required: true, equalTo: "#password", minlength: 6 }
};
var validation_reset_password_messages = {
    token: { required: 'Отсутствует Token' },
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    password: { required: 'Укажите пароль', minlength: 'Минимальная длина пароля 6 символов' },
    password_confirmation: { required: 'Повторите пароль',equalTo: 'Пароли должны совпадать', minlength: 'Минимальная длина пароля 6 символов' }
};

function guestFormValidation() {

    var signin = $("#signin-form").validate({
        rules: validation_signin ? validation_signin : {},
        onfocusout: false,
        messages: validation_signin_messages ? validation_signin_messages : {},
        errorPlacement : function(error, element){error.insertAfter(element);},
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
    var restore_pass = $("#restore-password-form").validate({
        rules: validation_restore_pass ? validation_restore_pass : {},
        onfocusout: false,
        messages: validation_restore_pass_messages ? validation_restore_pass_messages : {},
        errorPlacement : function(error, element){error.insertAfter(element);},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
                $("#error").remove();
                $(form).find('.btn-form-submit').elementDisabled(true);
            },
                options.success = function(response, status, xhr, jqForm){
                    $(form).find('.btn-form-submit').elementDisabled(false);
                    if(response.status){
                        $(form).replaceWith(response.responseText);
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
    var reset_pass = $("#reset-password-form").validate({
        rules: validation_reset_password ? validation_reset_password : {},
        onfocusout: false,
        messages: validation_reset_password_messages ? validation_reset_password_messages : {},
        errorPlacement : function(error, element){error.insertAfter(element);},
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
    var signupUL = $("#signup-ul-form").validate({
        rules: validation_signup_ul ? validation_signup_ul : {},
        onfocusout: false,
        messages: validation_signup_messages_ul ? validation_signup_messages_ul : {},
        errorPlacement : function(error, element){error.insertAfter(element);},
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
                            $(form).replaceWith('<p class="response">' + response.responseText + '</p>');
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
    var signupFL = $("#signup-fl-form").validate({
        rules: validation_signup_fl ? validation_signup_fl : {},
        onfocusout: false,
        messages: validation_signup_messages_fl ? validation_signup_messages_fl : {},
        errorPlacement : function(error, element){error.insertAfter(element);},
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
