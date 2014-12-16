var validation_payment_order = {
    order_id: { required: true },
    payment_date: { required: true },
    price: { required: true },
    payment_number: { required: true }
};
var validation_spayment_order_messages = {
    order_id: { required: 'Укажите заказ' },
    payment_date: { required: 'Укажите дату платежа' },
    price: { required: 'Укажите сумму платежа' },
    payment_number: { required: 'Укажите номер п/п' }
};

var validation_profile_ul = {
    email: { required: true, email: true },
    active: { required: true },
    title: { required: true },
    fio_manager: { required: true },
    fio_manager_rod: { required: true },
    manager: { required: true },
    statutory: { required: true },
    ogrn: { required: true },
    inn: { required: true },
    uraddress: { required: true },
    postaddress: { required: true },
    account_number: { required: true },
    account_kor_number: { required: true },
    bank: { required: true },
    bik: { required: true },
    email: { required: true, email: true },
    name: { required: true },
    phone: { required: true }
};
var validation_profile_messages_ul = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
    title: { required: 'Укажите название' },
    fio_manager: { required: 'Укажите фамилию, имя и отчество руководителя' },
    fio_manager_rod: { required: 'Укажите фамилию, имя и отчество руководителя в род. падеже' },
    manager: { required: 'Укажите должность' },
    statutory: { required: 'Укажите уставной документ' },
    ogrn: { required: 'Укажите ОГРН' },
    inn: { required: 'Укажите ИНН' },
    uraddress: { required: 'Укажите юридический адрес' },
    postaddress: { required: 'Укажите почтовый адрес' },
    account_number: { required: 'Укажите номер счета' },
    account_kor_number: { required: 'Укажите корреспондентский счет' },
    bank: { required: 'Укажите наименование банка' },
    bik: { required: 'Укажите БИК' },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail' },
    name: { required: 'Укажите контактное лицо' },
    phone: { required: 'Укажите контактный номер' }
};

var validation_profile_listener_company = {
    email: { required: true, email: true },
    active: { required: true },
    fio: { required: true },
    fio_dat: { required: true },
    position: { required: true },
    postaddress: { required: true },
    phone: { required: true },
    education: { required: true },
    education_document_data: { required: true },
    specialty: { required: true },
    educational_institution: { required: true }
};
var validation_profile_messages_listener_company = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
    fio: { required: 'Укажите Ф.И.О.' },
    fio_dat: { required: 'Укажите Ф.И.О. в дат. падеже' },
    position: { required: 'Укажите должность' },
    postaddress: { required: 'Укажите почтовый адрес' },
    phone: { required: 'Укажите контактный номер' },
    education: { required: 'Укажите образование' },
    education_document_data: { required: 'Укажите номер и дата выдачи документа' },
    specialty: { required: 'Укажите наименование специальности' },
    educational_institution: { required: 'Укажите наименование учебного заведения' }
};

var validation_profile_listener_individual = {
    email: { required: true, email: true },
    active: { required: true },
    fio: { required: true },
    position: { required: true },
    inn: { required: true },
    postaddress: { required: true },
    email: { required: true, email: true },
    phone: { required: true },

    position: { required: true },
    education: { required: true },
    document_education: { required: true },
    specialty: { required: true },
    educational_institution: { required: true },

    discount: { required: true }
};
var validation_signup_messages_listener_individual = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    inn: { required: 'Укажите ИНН' },
    postaddress: { required: 'Укажите почтовый адрес' },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail' },
    phone: { required: 'Укажите контактный номер' },

    position: { required: 'Укажите должность' },
    education: { required: 'Укажите образование' },
    document_education: { required: 'Укажите номер и дату выдачи' },
    specialty: { required: 'Укажите наименование специальности' },
    educational_institution: { required: 'Укажите наименование учебного заведения' },
    discount: { required: 'Укажите скидку' }
};

var validation_order_update = {
    number: { required: true},
    created_at: { required: true }
};
var validation_order_update_messages = {
    number: { required: 'Укажите номер заказа' },
    created_at: { required: 'Укажите дату создания заказа' }
};

function scrollToError(elem) {
    $('html, body').animate({scrollTop: elem.offset().top}, 200);
}

$(function(){
    function SetListenerAccess($this){

        $.ajax({
            url: $($this).attr('data-action'),
            data : { 'value' : $value },
            type: 'POST', dataType: 'json',
            beforeSend: function(){
                $($($this)).elementDisabled(true);
            },
            success: function(response,textStatus,xhr){
                $($($this)).elementDisabled(false);
            },
            error: function(xhr,textStatus,errorThrown){
                $($($this)).elementDisabled(false);
            }
        });
    }
    $(".js-set-listeners-access").click(function(){

        var $this = this;
        var $listeners = '';
        $(".js-set-listener-access").each(function(index,element){
            if($(element).is(':checked')){
                $listeners = $listeners + '{"'+ $(element).val() + '":"' + '1"},';
            }else{
                $listeners = $listeners + '{"' + $(element).val() + '":"' + '0"},';
            }
        });
        $listeners = '['+$listeners.slice(0, -1)+']';

        $.ajax({
            url: $($this).attr('data-action'),
            data : { courses : $.parseJSON($listeners) },
            type: 'POST', dataType: 'json',
            beforeSend: function(){
                $($this).elementDisabled(true);
            },
            success: function(response,textStatus,xhr){
                $($this).elementDisabled(false);
            },
            error: function(xhr,textStatus,errorThrown){
                $($this).elementDisabled(false);
            }
        });
    });
    $(".js-delete-order").click(function() {
        var $this = this;
        var $order = $($this).data('order-number');
        var currentTabCountOrder = 0;
        $.SmartMessageBox({
            title : "Удалить заказ №"+$($this).data('order-number')+"?",
            content : "",
            buttons : '[Нет][Да]'
        },function(ButtonPressed) {
            if(ButtonPressed == "Да") {
                $.ajax({
                    url: $($this).parent('form').attr('action'),
                    type: 'DELETE',
                    dataType: 'json',
                    beforeSend: function(){$($this).elementDisabled(true);},
                    success: function(response, textStatus, xhr){
                        if(response.status == true){
                            showMessage.constructor('Удаление закза', response.responseText);
                            showMessage.smallSuccess();
                            $(".js-delete-order[data-order-number='"+$order+"']").parents('.js-tab-current').each(function(){
                                currentTabCountOrder = $("a[href='#"+$(this).attr('id')+"']").find('.filter-count').html();
                                $("a[href='#"+$(this).attr('id')+"']").find('.filter-count').html(currentTabCountOrder-1);
                            });
                            $(".js-delete-order[data-order-number='"+$order+"']").parents('.js-orders-line').fadeOut(500,function(){$(this).remove();});
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удалить ' + essence_name, 'Возникла ошибка. Обновите страницу и повторите снова.');
                            showMessage.smallError();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удалить ' + essence_name, 'Возникла ошибка. Повторите снова.');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });
});

function moderatorFormValidation() {

    var PaymentNumberInsert = $("#payment-number-insert-form").validate({
        rules: validation_payment_order ? validation_payment_order : {},
        messages: validation_spayment_order_messages ? validation_spayment_order_messages : {},
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
    var PaymentNumberEdit = $("#payment-number-update-form").validate({
        rules: validation_payment_order ? validation_payment_order : {},
        messages: validation_spayment_order_messages ? validation_spayment_order_messages : {},
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
    var profileListenerCompany = $("#company-profile-listener-form").validate({
        rules: validation_profile_listener_company ? validation_profile_listener_company : {},
        messages: validation_profile_messages_listener_company ? validation_profile_messages_listener_company : {},
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
    var profileListenerIndividual = $("#individual-profile-listener-form").validate({
        rules: validation_profile_listener_individual ? validation_profile_listener_individual : {},
        messages: validation_signup_messages_listener_individual ? validation_signup_messages_listener_individual : {},
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
    var OrderUpdate = $("#order-edit-form").validate({
        rules: validation_order_update ? validation_order_update : {},
        messages: validation_order_update_messages ? validation_order_update_messages : {},
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
