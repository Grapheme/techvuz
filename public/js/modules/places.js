/** 
 * Author: Zelenskiy Alexander
 */

var essence = 'place';
var essence_name = 'место'
 
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
	
	var news = $("#" + essence + "-form").validate({
		rules:{
			title: {required : true}
		},
		messages : {
			title : {required : 'Укажите название'}
		},
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
			}
			$(form).ajaxSubmit(options);
		}
	});
}