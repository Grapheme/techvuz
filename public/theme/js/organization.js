var validation_signup_listener = {
    group_id: { required: true },
    organization_id: { required: true },
    fio: { required: true },
    fio_dat: { required: true },
    position: { required: true },
    email: {
        required: true,
        email: true
        //remote: {
        //    url: "/check-email",
        //    type: "post",
        //    data: {
        //        email: function() {
        //            return $("#signup-listener-form input[name='email']").val();
        //        }
        //    },
        //    dataType: "json",
        //    success: function (data) {
        //        if (data.email == true) {
        //            message: {
        //                name: data.message;
        //            }
        //        }
        //    }
        //}
    },
    postaddress: { required: true },
    phone: { required: true },
    education: { required: true },
    education_document_data: { required: true },
    educational_institution: { required: true },
    specialty: { required: true }
};
var validation_signup_messages_listener = {
    group_id: { required: 'Укажите группу' },
    organization_id: { required: 'Укажите организацию' },
    fio: { required: 'Укажите Ф.И.О.' },
    fio_dat: { required: 'Укажите Ф.И.О. в дат. падеже' },
    position: { required: 'Укажите должность' },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail','remote': 'Email уже зарегистрирован' },
    postaddress: { required: 'Укажите почтовый адрес' },
    phone: { required: 'Укажите контактный номер' },
    education: { required: 'Укажите образование' },
    education_document_data: { required: 'Укажите номер и дату выдачи документа об образовании' },
    educational_institution: { required: 'Укажите наименование учебного заведения' },
    specialty: { required: 'Укажите наименование специальности' }
};

var validation_profile_listener = {
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
var validation_profile_messages_listener = {
    fio: { required: 'Укажите Ф.И.О.' },
    fio_dat: { required: 'Укажите Ф.И.О. в дат. падеже' },
    position: { required: 'Укажите должность' },
    postaddress: { required: 'Укажите адрес' },
    phone: { required: 'Укажите номер телефона' },
    education: { required: 'Укажите образование' },
    education_document_data: { required: 'Укажите номер и дата выдачи документа' },
    specialty: { required: 'Укажите наименование специальности' },
    educational_institution: { required: 'Укажите наименование учебного заведения' }
};

var validation_profile_ul = {
    group_id: { required: true },
    title: { required: true },
    fio_manager: { required: true },
    fio_manager_rod: { required: true },
    manager: { required: true },
    statutory: { required: true },
    ogrn: { required: true },
    inn: { required: true },
    kpp: { required: true },
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
    group_id: { required: 'Укажите группу' },
    title: { required: 'Укажите название' },
    fio_manager: { required: 'Укажите фамилию, имя и отчество руководителя' },
    fio_manager_rod: { required: 'Укажите фамилию, имя и отчество руководителя в род. падеже' },
    manager: { required: 'Укажите должность' },
    statutory: { required: 'Укажите уставной документ' },
    ogrn: { required: 'Укажите ОГРН' },
    inn: { required: 'Укажите ИНН' },
    kpp: { required: 'Укажите КПП' },
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

function scrollToError(elem) {
    $('html, body').animate({
        scrollTop: elem.offset().top
    }, 200);
}

$(function(){

    $('.js-delete-temp-order').click( function(){
        var $self = $(this);
        var $parent = $(this).parents('.orders-li.non-paid-order');

        $.SmartMessageBox({
            title : "Удалить заказ?",
            content : "",
            buttons : '[Нет][Да]'
        },function(ButtonPressed) {
            if(ButtonPressed == "Да") {
                $parent.remove();
                $.removeCookie('ordering');
            }
        });
    });

    $(".js-delete-order").click(function() {
        var $this = this;
        var $order = $($this).data('order-number');
        var currentTabCountOrder = 0;
        $.SmartMessageBox({
            title : "Удалить заказ №"+$order+"?",
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
                            showMessage.constructor('Удаление заказа', response.responseText);
                            showMessage.smallSuccess();
                            $(".js-delete-order[data-order-number='"+$order+"']").parents('.js-tab-current').each(function(){
                                currentTabCountOrder = $("a[href='#"+$(this).attr('id')+"']").find('.filter-count').html();
                                $("a[href='#"+$(this).attr('id')+"']").find('.filter-count').html(currentTabCountOrder-1);
                            });
                            $(".js-delete-order[data-order-number='"+$order+"']").parents('.js-orders-line').fadeOut(500,function(){$(this).remove();});
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удаление заказа', 'Возникла ошибка. Обновите страницу и повторите снова.');
                            showMessage.smallError();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удаление заказа', 'Возникла ошибка. Повторите снова.');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });
    $(".js-delete-listener").click(function() {
        var $this = this;
        $.SmartMessageBox({
            title : "Удалить сотрудника?",
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
                            showMessage.constructor('Удаление сотрудника', response.responseText);
                            showMessage.smallSuccess();
                            if(response.redirect !== false){
                                BASIC.RedirectTO(response.redirect);
                            }else{
                                $($this).parents('tr').remove();
                            }
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удаление сотрудника', 'Возникла ошибка. Обновите страницу и повторите снова.');
                            showMessage.smallError();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удаление сотрудника', 'Возникла ошибка. Повторите снова.');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });
});

function organizationFormValidation() {

    var signupListener = $("#signup-listener-form").validate({
        rules: validation_signup_listener ? validation_signup_listener : {},
        messages: validation_signup_messages_listener ? validation_signup_messages_listener : {},
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
                            $(".cabinet-tabs h3").remove();
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
