/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */

$(function(){
	
	
});

function runFormValidation(){
	
	var SignIn = $("#signin-form").validate({
		rules:{
			login: {required : true, email : true},
			password : {required : true, minlength : 6},
		},
		messages : {
			login : {required : 'Введите Ваш адрес электронной почты',email : 'Введите правильный адрес электронной почты'},
			password : {required : 'Введите пароль',minlength : 'Минимальная длина пароля 6 символа'},
		},
		errorPlacement : function(error, element) {
			error.insertAfter(element.parent());
		},
		submitHandler: function(form) {
			var options = {target: null,dataType:'json',type:'post'};
			options.beforeSubmit = function(formData,jqForm,options){
				$(form).find('button[type="submit"]').elementDisabled(true);
			},
			options.success = function(response,status,xhr,jqForm){
				if(response.status){
					BASIC.RedirectTO(response.redirect);
				}else{
					$(form).find('button[type="submit"]').elementDisabled(false);
					showMessage.constructor(response.responseText,response.responseErrorText);
					showMessage.smallError();
				}
			}
			$(form).ajaxSubmit(options);
		}
	});
};