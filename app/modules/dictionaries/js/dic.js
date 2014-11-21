
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