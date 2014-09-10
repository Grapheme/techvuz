/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
$(function(){

    $(".remove-group").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить группу пользователей?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',
                    dataType: 'json',
					beforeSend: function(){ $($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление группы пользователей', response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление группы пользователей','Возникла ошибка. Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление группы пользователей','Возникла ошибка. Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
});

function runFormValidation() {
	
	var validation = $("#group-form").validate({
		rules:{
			name: {required : true},
			desc: {required : true},
			dashboard: {required : true},
		},
		messages : {
			name : {required : 'Укажите название группы'},
			desc : {required : 'Укажите описание группы'},
			dashboard : {required : 'Укажите стартовую страницу группы'},
		},
		errorPlacement : function(error, element){error.insertAfter(element.parent());},
		submitHandler: function(form) {
			var options = {target: null,dataType:'json',type:'post'};
			options.beforeSubmit = function(formData,jqForm,options){
				$(form).find('.btn-form-submit').elementDisabled(true);
			},
			options.success = function(response,status,xhr,jqForm){
				$(form).find('.btn-form-submit').elementDisabled(false);
				if(response.status){
					if(response.redirect !== false){
						BASIC.RedirectTO(response.redirect);
					}
					showMessage.constructor(response.responseText,'');
					showMessage.smallSuccess();
				}else{
					showMessage.constructor(response.responseText,response.responseErrorText);
					showMessage.smallError();
				}
			}
			$(form).ajaxSubmit(options);
		}
	});
}
