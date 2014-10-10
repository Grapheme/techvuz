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
    phone: { required: true }
};
var validation_profile_messages_ul = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
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
    phone: { required: 'Укажите контактный номер' }
};

var validation_profile_listener_company = {
    email: { required: true, email: true },
    active: { required: true },
    fio: { required: true },
    position: { required: true },
    postaddress: { required: true },
    phone: { required: true },
    education: { required: true },
    place_work: { required: true },
    year_study: { required: true },
    specialty: { required: true }
};
var validation_profile_messages_listener_company = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    postaddress: { required: 'Укажите почтовый адрес' },
    phone: { required: 'Укажите контактный номер' },
    education: { required: 'Укажите образование' },
    place_work: { required: 'Укажите образование' },
    year_study: { required: 'Укажите год обучения' },
    specialty: { required: 'Укажите специальность' }
};

var validation_profile_listener_individual = {
    email: { required: true, email: true },
    active: { required: true },
    fio: { required: true },
    position: { required: true },
    inn: { required: true },
    postaddress: { required: true },
    email: { required: true, email: true },
    phone: { required: true }
};
var validation_signup_messages_listener_individual = {
    email: { required: 'Укажите Email', email: 'Неверный адрес Email' },
    active: { required: 'Укажите статус аккаунта' },
    fio: { required: 'Укажите Ф.И.О.' },
    position: { required: 'Укажите должность' },
    inn: { required: 'Укажите ИНН' },
    postaddress: { required: 'Укажите почтовый адрес' },
    email: { required: 'Укажите контактный E-mail','email': 'Неверно указан формат данных' },
    phone: { required: 'Укажите контактный номер' }
};

function scrollToError(elem) {
    $('html, body').animate({
        scrollTop: elem.offset().top
    }, 200);
}

$(function(){

    function SetListenerAccess($this){
        $.ajax({
            url: $($this).attr('data-action'),
            type: 'POST', dataType: 'json',
            beforeSend: function(){},
            success: function(response,textStatus,xhr){
                if(response.status == true) {
                    if (response.responseOrderStatus !== false){
                        $(".js-set-order-payment-status").val(response.responseOrderStatus);
                        $(".js-set-order-payment-status").selectmenu("refresh");
                        console.log(response.responseOrderStatus);
                    }
                }
            },
            error: function(xhr,textStatus,errorThrown){}
        });
    }

    $(".js-check-all-payments").click(function(){
        $(".js-set-listener-access").each(function(index,element){SetListenerAccess(element);});
    });
    $(".js-uncheck-all-payments").click(function(){
        $(".js-set-listener-access").each(function(index,element){SetListenerAccess(element);});
    });

    $(".js-set-order-payment-status").selectmenu({
        change: function( event, ui ) {
            var $this = this;
            var $status = ui.item.value;
            $.ajax({
                url: $($this).attr('data-action'),
                data: { status : $status},
                type: 'POST', dataType: 'json',
                beforeSend: function(){},
                success: function(response,textStatus,xhr){
                    if(response.status == true) {
                        if ($status == 2) {
                            $(".js-set-listener-access").prop('checked', true);
                        } else if($status == 4) {
                            $(".js-set-listener-access").prop('checked', true);
                        } else if($status == 5) {
                            $(".js-set-listener-access").prop('checked', true);
                        } else if($status == 6) {
                            $(".js-set-listener-access").prop('checked', false);
                        }
                    }
                },
                error: function(xhr,textStatus,errorThrown){}
            });
        }
    });

    $(".js-set-listener-access").click(function(){SetListenerAccess(this);});
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
    var profileListenerCompany = $("#individual-profile-listener-form").validate({
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

}
