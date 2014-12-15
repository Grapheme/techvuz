var validation_profile_fl = {
    fio: { required: true },
    fio_rod: { required: true },
    passport_seria: { required: true },
    passport_number: { required: true },
    passport_data: { required: true },
    passport_date: { required: true },
    code: { required: true },
    postaddress: { required: true },
    email: { required: true, email: true },
    phone: { required: true },

    position: { required: true },
    education: { required: true },
    document_education: { required: true },
    specialty: { required: true },
    educational_institution: { required: true }
};
var validation_profile_messages_fl = {
    fio: { required: 'Укажите Ф.И.О.' },
    fio_rod: { required: 'Укажите Ф.И.О. в род. падеже' },
    passport_seria: { required: 'Укажите серию паспорта' },
    passport_number: { required: 'Укажите Номер паспорта' },
    passport_data: { required: 'Укажите кем выдан паспорт' },
    passport_date: { required: 'Укажите дату выдачи паспорта' },
    code: { required: 'Укажите rод подразделения' },
    postaddress: { required: 'Укажите почтовый адрес' },
    email: { required: 'Укажите контактный E-mail','email': 'Некорректный E-mail' },
    phone: { required: 'Укажите контактный номер' },

    position: { required: 'Укажите должность' },
    education: { required: 'Укажите образование' },
    document_education: { required: 'Укажите номер и дату выдачи' },
    specialty: { required: 'Укажите наименование специальности' },
    educational_institution: { required: 'Укажите наименование учебного заведения' }
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
});

function organizationFormValidation() {

    var profileIndividual = $("#profile-individual-form").validate({
        rules: validation_profile_fl ? validation_profile_fl : {},
        messages: validation_profile_messages_fl ? validation_profile_messages_fl : {},
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
