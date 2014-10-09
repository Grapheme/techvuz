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

function scrollToError(elem) {
    $('html, body').animate({
        scrollTop: elem.offset().top
    }, 200);
}

function moderatorFormValidation() {

    var PaymentNumber = $("#payment-number-form").validate({
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

}
