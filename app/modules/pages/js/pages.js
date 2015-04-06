/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */

$(function () {

    $(".remove-page").click(function () {
        var $this = this;
        $.SmartMessageBox({
            title: "Удалить страницу?",
            content: "",
            buttons: '[Нет][Да]'
        }, function (ButtonPressed) {
            if (ButtonPressed == "Да") {
                $.ajax({
                    url: $($this).parent('form').attr('action'),
                    type: 'DELETE', dataType: 'json',
                    beforeSend: function () {
                        $($this).elementDisabled(true);
                    },
                    success: function (response, textStatus, xhr) {
                        if (response.status == true) {
                            showMessage.constructor('Удаление страницы', response.responseText);
                            showMessage.smallSuccess();
                            $($this).parents('tr').fadeOut(500, function () {
                                $(this).remove();
                            });
                        } else {
                            $($this).elementDisabled(false);
                            showMessage.constructor('Удаление страницы', 'Возникла ошибка.Обновите страницу и повторите снова');
                            showMessage.smallError();
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $($this).elementDisabled(false);
                        showMessage.constructor('Удаление страницы', 'Возникла ошибка.Повторите снова');
                        showMessage.smallError();
                    }
                });
            }
        });
        return false;
    });


});

function runFormValidation() {

    var editing = $("#page-form").validate({
        rules: {
            name: {required: true}
        },
        messages: {
            name: {required: 'Укажите название страницы'},
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        },
        submitHandler: function (form) {
            var options = {target: null, dataType: 'json', type: 'post'};
            options.beforeSubmit = function (formData, jqForm, options) {
                $(form).find('.btn-form-submit').elementDisabled(true);
            },
                options.success = function (response, status, xhr, jqForm) {
                    $(form).find('.btn-form-submit').elementDisabled(false);
                    if (response.status) {
                        if (response.redirect !== false) {
                            BASIC.RedirectTO(response.redirect);
                        }
                        showMessage.constructor(response.responseText, '');
                        showMessage.smallSuccess();
                    } else {
                        showMessage.constructor(response.responseText, response.responseErrorText);
                        showMessage.smallError();
                    }
                }
            $(form).ajaxSubmit(options);
        }
    });
}


// some JS code
$(document).on('click', '.pages_block_redactor_toggle', function () {

    //var element = $('#blockEditModal textarea');
    var element = $(this).parent().parent().find('textarea');
    //console.log(element);
    var inited = $(element).parent().find('.redactor_editor').attr('class');
    //console.log(inited);

    if (inited) {

        $(element).redactor('destroy');
        $('input[type=hidden][name="settings[editor_state]"]').val('0');

    } else {

        $('input[type=hidden][name="settings[editor_state]"]').val('1');

        activate_block_editor(element)

        /*
        if ($(element).hasClass('redactor')) {
            $(element).redactor(imperavi_config || {});
        } else if ($(element).hasClass('redactor-no-filter')) {
            $(element).redactor(imperavi_config_no_filter || {});
        }
        */
    }

});


function activate_block_editor(element) {

    if ($(element).hasClass('redactor')) {

        $(element).redactor(imperavi_config || {});

    } else if ($(element).hasClass('redactor-no-filter')) {

        $(element).redactor(imperavi_config_no_filter || {});
    }
}


$(document).on('click', 'button#reset_block_content', function () {

    var default_block_content = $(this).parents('form').find('#default_block_content').html();
    console.log(default_block_content);
    if (default_block_content != '')
        $(this).parents('form').find('.editor_block_content').html(default_block_content);
    return true;

});