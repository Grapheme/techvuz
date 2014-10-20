$(function(){

    $(".create-dublicate-test").click(function() {
        var $this = this;
        $.SmartMessageBox({
            title : "Создать копию теста?",
            content : "",
            buttons : '[Нет][Да]'
        },function(ButtonPressed) {
            if(ButtonPressed == "Да"){
                $($this).parents('form').submit();
            }
        });
        return false;
    });

    $(".remove-" + essence_test).click(function() {
        var $this = this;
        $.SmartMessageBox({
            title : "Удалить " + essence_test_name + "?",
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
                            showMessage.constructor('Удалить ' + essence_test_name, response.responseText);
                            showMessage.smallSuccess();
                            if(response.redirect !== false){
                                BASIC.RedirectTO(response.redirect);
                            }
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удалить ' + essence_test_name, 'Возникла ошибка. Обновите страницу и повторите снова.');
                            showMessage.smallError();
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удалить ' + essence_test_name, 'Возникла ошибка. Повторите снова.');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });

	$(".remove-" + essence_question).click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить " + essence_question_name + "?",
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
							showMessage.constructor('Удалить ' + essence_question_name, response.responseText);
							showMessage.smallSuccess();
							//$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
                            location.reload(true);
                        } else {
							$($this).elementDisabled(false);
							showMessage.constructor('Удалить ' + essence_question_name, 'Возникла ошибка. Обновите страницу и повторите снова.');
							showMessage.smallError();
						}
					},
					error: function(xhr, textStatus, errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удалить ' + essence_question_name, 'Возникла ошибка. Повторите снова.');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});

    $(".remove-" + essence_answer).click(function() {
		var $this = this;
		$.SmartMessageBox({
			title : "Удалить " + essence_answer_name + "?",
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
							showMessage.constructor('Удалить ' + essence_answer_name, response.responseText);
							showMessage.smallSuccess();
							//$($this).parents('tr').fadeOut(500,function(){$(this).remove();});
                            location.reload(true);
						} else {
							$($this).elementDisabled(false);
							showMessage.constructor('Удалить ' + essence_answer_name, 'Возникла ошибка. Обновите страницу и повторите снова.');
							showMessage.smallError();
						}
					},
					error: function(xhr, textStatus, errorThrown){
						$($this).elementDisabled(false);
						showMessage.constructor('Удалить ' + essence_answer_name, 'Возникла ошибка. Повторите снова.');
						showMessage.smallError();
					}
				});
			}
		});
		return false;
	});

});


function runFormValidation() {

    return null;
}