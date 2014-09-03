/** 
 * Author: Zelenskiy Alexander
 */

$(function(){
	
	$(".remove-" + essence).click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить " + essence_name + "?",
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
							showMessage.constructor('Удалить ' + essence_name, response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
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

                if(typeof response.form_values != 'undefined' && response.form_values.length) {
                    $(response.form_values).each(function(i) {
                        //alert(i + ' > ' + data[i] + " | ");
                        $.each(response.form_values, function(i, val) {
                            $(i).val(val).text(val);
                        });
                    });
                }

                //alert(onsuccess_function);
                if (typeof onsuccess_function != 'undefined') {
                    setTimeout(onsuccess_function, 100);
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
