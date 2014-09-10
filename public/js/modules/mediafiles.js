/** 
 * Author: Zelenskiy Alexander
 */

function runFormValidation() {

	var validation = $("#" + essence + "-form").validate({
        rules: validation_rules ? validation_rules : {},
		messages: validation_messages ? validation_messages : {},
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
					}
					showMessage.constructor(response.responseText, '');
					showMessage.smallSuccess();
				}else{
					showMessage.constructor(response.responseText, response.responseErrorText);
					showMessage.smallError();
				}

                if(response.form_values.length) {
                    $(response.form_values).each(function(i) {
                        //alert(i + ' > ' + data[i] + " | ");
                        $.each(response.form_values, function(i, val) {
                            $(i).val(val).text(val);
                        });
                    });
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
                    console.log(xhr);
                    var msg_title = textStatus;
                    var msg_body = xhr.responseText;
                }

				$(form).find('.btn-form-submit').elementDisabled(false);
				showMessage.constructor(msg_title, msg_body);
				showMessage.smallError();
			}
			$(form).ajaxSubmit(options);
		}
	});
}
