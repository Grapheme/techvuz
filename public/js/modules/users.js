/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
$(function(){

    $(".remove-user").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить пользователя?",
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
							showMessage.constructor('Удаление пользователя', response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление пользователя','Возникла ошибка. Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление пользователя','Возникла ошибка. Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
});

function runFormValidation(){

	var validation = $("#user-form").validate({
		rules: {
			name: { required: true },
			//surname: { required: true },
			group : { required: true},
			email: { required: true, email: true },
			password1 : {
                required: true,
                minlength: 6
            },
			password2 : {
                required: true,
                pass: true,
                minlength: 6
            },
		},
		messages: {
			name: {
                required: 'Введите Ваше имя'
            },
			//surname: { required: 'Введите Вашу фамилию' },
			group: {
                required: 'Выберите группу для пользователя'
            },
			email: {
                required: 'Введите Ваш адрес электронной почты',
                email: 'Введите корректный адрес электронной почты'
            },
			password1: {
                required: 'Введите пароль',
                minlength: 'Минимальная длина пароля 6 символа',
            },
			password2: {
                required: 'Введите пароль еще раз',
                minlength: 'Минимальная длина пароля 6 символа',
                pass: 'Пароли должны совпадать'
            },
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		},
		submitHandler: function(form) {
			var options = {target:null, dataType:'json', type:'post'};
			options.beforeSubmit = function(formData,jqForm,options){
				$(form).find('.btn-form-submit').elementDisabled(true);
			},
			options.success = function(response,status,xhr,jqForm){
				$(form).find('.btn-form-submit').elementDisabled(false);
				if(response.status){
					//$(form).replaceWith(response.responseText);
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


	var validation = $("#user-changepass-form").validate({
		rules: {
			password1 : {
                required: true,
                minlength: 6
            },
			password2 : {
                required: true,
                pass: true,
                minlength: 6
            },
		},
		messages: {
			password1: {
                required: 'Введите пароль',
                minlength: 'Минимальная длина пароля 6 символа',
            },
			password2: {
                required: 'Введите пароль еще раз',
                minlength: 'Минимальная длина пароля 6 символа',
                pass: 'Пароли должны совпадать'
            },
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		},
		submitHandler: function(form) {
			var options = {target:null, dataType:'json', type:'post'};
			options.beforeSubmit = function(formData,jqForm,options){
				$(form).find('.btn-form-submit').elementDisabled(true);
			},
			options.success = function(response,status,xhr,jqForm){
				$(form).find('.btn-form-submit').elementDisabled(false);
				if(response.status){
					//$(form).replaceWith(response.responseText);
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


    jQuery.validator.addMethod("pass", function(value, element){
        if( $("input[name=password1]").val() == $("input[name=password2]").val() ) {
            return true;
        }
        return false;
    });

};