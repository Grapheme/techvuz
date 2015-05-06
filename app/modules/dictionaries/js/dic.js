
/**
 * Функционал для восстановления резервных копий записей словарей
 */
$('.restore_version').click(function(e) {
    e.preventDefault();

    // get the link
    var url = $(this).attr('data-url');

    // ask verification
    $.SmartMessageBox({
        title : "<i class='fa fa-refresh txt-color-orangeDark'></i> Восстановить эту резервную копию?",
        content : "Текущая версия будет сохранена",
        buttons : '[Нет][Да]'
    }, function(ButtonPressed) {
        if (ButtonPressed == "Да") {
            location.href = url;
        }
    });
});

/**
 * Функционал для кнопки удаления записи DicVal (в меню) при ее редактировании
 */

$(function(){

    $(".remove-dicval-record").click(function() {
        var $this = this;
        $.SmartMessageBox({
            title : "Удалить данную запись?",
            content : "Восстановить ее будет невозможно",
            buttons : '[Нет][Да]'
        },function(ButtonPressed) {
            if(ButtonPressed == "Да") {
                $.ajax({
                    url: $($this).attr('href'),
                    type: 'DELETE',
                    dataType: 'json',
                    beforeSend: function(){$($this).elementDisabled(true);},
                    success: function(response, textStatus, xhr){
                        if(response.status == true){
                            //showMessage.constructor('Удаление', response.responseText);
                            //showMessage.smallSuccess();
                            //$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
                            location.href = $($this).attr('data-goto');
                            return false;
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удаление', 'Возникла ошибка. Обновите страницу и повторите снова.');
                            showMessage.smallError();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удаление', 'Возникла ошибка. Повторите снова.');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });
});



/** 
 * Функционал для кнопки удаления записи (в списке)
 */
$(function(){
	
	$(".remove-dicval-list").click(function(e) {

        e.preventDefault();

		var $this = this;

        $.SmartMessageBox({
			title : "Удалить запись?",
			content : "",
			buttons : '[Нет][Да]'
		}, function(ButtonPressed) {

			if(ButtonPressed == "Да") {

				$.ajax({
					url: $($this).parent('form').attr('action'),
					type: 'DELETE',
                    dataType: 'json',
					beforeSend: function(){$($this).elementDisabled(true);},
					success: function(response, textStatus, xhr){
						if(response.status == true){
							showMessage.constructor('Удалить запись', response.responseText);
							showMessage.smallSuccess();

							//$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
                            $('.dd-item[data-id=' + $($this).parents('.dd-item').attr('data-id') + ']').slideUp();

						} else {
							$($this).elementDisabled(false);
							showMessage.constructor('Удалить запись', 'Возникла ошибка. Обновите страницу и повторите снова.');
							showMessage.smallError();
						}
					},
					error: function(xhr, textStatus, errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удалить запись', 'Возникла ошибка. Повторите снова.');
						showMessage.smallError();
					}
				});

			}
		});
		return false;
	});
});



function runDicValFormValidation() {

    var essence = 'dicval';

    var validation = $("#" + essence + "-form").validate({
        rules: validation_rules ? validation_rules : {},
		messages: validation_messages ? validation_messages : {},
		errorPlacement: function(error, element){error.insertAfter(element.parent());},
        ignore: [],
		submitHandler: function(form) {

            if (CheckDicvalSlugUnique) {
                /**
                 * Проверяем системное имя на уникальность
                 */

                var $proceed = false;

                $.ajax({
                    type: "POST",
                    async: false,
                    url: base_url + "/ajax/check-dicval-slug-unique",
                    data: $(form).serialize()
                })
                    .done(function(data, textStatus, jqXHR) {

                        //console.log(data);
                        if (!data.status) {
                            showMessage.constructor(data.responseText, data.new_slug);
                            showMessage.smallError();
                            //return false;
                            var $proceed = true;
                        } else {
                            DicVal_values_validation(form);
                        }

                    })
                    .fail(function( jqXHR, textStatus, errorThrown) {
                        //
                    })
                    .always(function(data, textStatus, jqXHR) {
                        $(form).find('.btn-form-submit').elementDisabled(false);
                    });

            } else {

                DicVal_values_validation(form);

            }

		}
	});
}



function DicVal_values_validation(form) {

    /*****************************************************************************/

    var options = {target:null, dataType:'json', type:'post'};
    options.beforeSubmit = function(formData, jqForm, options){

        $(form).find('.btn-form-submit').elementDisabled(true);

        //return false;

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

            //alert(typeof(onsuccess_function));
            //alert(onsuccess_function);
            if (typeof onsuccess_function == 'function') {
                setTimeout(onsuccess_function(response), 100);
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