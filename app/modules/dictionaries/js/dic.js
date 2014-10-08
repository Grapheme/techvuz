
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