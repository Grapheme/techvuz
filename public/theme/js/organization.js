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
}
