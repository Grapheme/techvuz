/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
$(function(){
	
	$(".remove-catalog").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить каталог продукции?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление каталога',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление каталога','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление каталога','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
	$("#add-catalog-field").click(function(){
		$("#catalog-fields-list li:last").clone().appendTo($("#catalog-fields-list"));
		$("#catalog-fields-list li:last").find('input').val('');
	});
	$("#remove-catalog-field").click(function(){
		if($("#catalog-fields-list li").length > 1){
			$("#catalog-fields-list li:last").remove();
		}
	});
	$(".remove-catalog-category-group").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить группу категорий продукции?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление группы категорий',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление группы категорий','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление группы категорий','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
	$(".remove-catalog-manufacturer").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить производителя продукции?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление производителя продукции',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление производителя продукции','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление производителя продукции','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
	$(".remove-catalog-category").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить категорию продукции?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление категории',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление категории','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление категории','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
	$(".remove-catalog-product").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить продукт?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление категории',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление продукта','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление продукта','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
	$(".remove-free-image").click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить изображение?",
			content : "",
			buttons : '[Нет][Да]'
		},function(ButtonPressed) {
			if(ButtonPressed == "Да") {
				$.ajax({
					url: $($this).parents('tr').attr('data-action'),
					type: 'DELETE',dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response,textStatus,xhr){
						if(response.status == true){
							showMessage.constructor('Удаление изображения',response.responseText);
							showMessage.smallSuccess();
							$($this).parents('tr').fadeOut(500,function(){
								$(this).remove();
								if($("#table-free-images tr").length == 0){
									$("#table-free-images").remove();
									$("#li-free-images").remove();
								}
							});
						}else{
							$($this).elementDisabled(false);
							showMessage.constructor('Удаление изображения','Возникла ошибка.Обновите страницу и повторите снова');
							showMessage.smallError();
						}
					},
					error: function(xhr,textStatus,errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удаление изображения','Возникла ошибка.Повторите снова');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});
});

function runFormValidation() {
	
	var editingCatalog = $("#catalog-form").validate({
		rules:{
			title: {required : true},
		},
		messages : {
			title : {required : 'Укажите название каталога'},
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
	var editingCategoryGroup = $("#catalog-category-group-form").validate({
		rules:{
			title: {required : true},
		},
		messages : {
			title : {required : 'Укажите название группы категорий'},
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
	var editingCategory = $("#catalog-category-form").validate({
		rules:{
			title: {required : true},
		},
		messages : {
			title : {required : 'Укажите название категории'},
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
	var editingProduct = $("#catalog-product-form").validate({
		rules:{
			title: {required : true},
		},
		messages : {
			title : {required : 'Укажите название продукта'},
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
					BASIC.inputChanged = false;
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
	var editingManufacturer = $("#catalog-manufacturer-form").validate({
		rules:{
			title: {required : true},
		},
		messages : {
			title : {required : 'Укажите название производителя'},
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
					BASIC.inputChanged = false;
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