var validation_rules_ul = {
    group_id: { required: true },
    organization: { required: true },
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
var validation_messages_ul = {
    group_id: { required: 'Укажите группу' },
    organization: { required: 'Укажите название' },
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

var validation_rules_fl = {
    group_id: { required: true },
    fio: { required: true },
    position: { required: true },
    inn: { required: true },
    postaddress: { required: true },
    email: { required: true, email: true },
    name: { required: true },
    phone: { required: true },
    consent: { required: true }
};
var validation_messages_fl = {
    group_id: { required: 'Укажите группу' },
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    inn: { required: 'Укажите ИНН' },
    postaddress: { required: 'Укажите почтовый адрес' },
    email: { required: 'Укажите контактный E-mail','email': 'Неверно указан формат данных' },
    name: { required: 'Укажите контактное лицо' },
    phone: { required: 'Укажите контактный номер' },
    consent: { required: '' }
};

function runFormValidation() {

    var signupUL = $("#signup-ul-form").validate({
        rules: validation_rules_ul ? validation_rules_ul : {},
        messages: validation_messages_ul ? validation_messages_ul : {},
        errorPlacement : function(error, element){error.insertAfter(element.parent());},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
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
        rules: validation_rules_fl ? validation_rules_fl : {},
        messages: validation_messages_fl ? validation_messages_fl : {},
        errorPlacement : function(error, element){error.insertAfter(element.parent());},
        submitHandler: function(form) {
            var options = {target:null, dataType:'json', type:'post'};
            options.beforeSubmit = function(formData,jqForm,options){
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
