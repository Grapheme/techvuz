// some JS code
$(document).on('click', '.pages_block_redactor_toggle', function(){

    //var element = $('#blockEditModal textarea');
    var element = $(this).parent().parent().find('textarea');
console.log(element);
    var inited = $(element).parent().find('.redactor_editor').attr('class');
console.log(inited);

    if( inited ) {
        $(element).redactor('destroy');
    } else {
        if ( $(element).hasClass('redactor') )
            $(element).redactor(imperavi_config || {});
        else if ( $(element).hasClass('redactor-no-filter') )
            $(element).redactor(imperavi_config_no_filter || {});
    }

});


$(document).on('click', 'button#reset_block_content', function(){

    var default_block_content = $(this).parents('form').find('#default_block_content').html();
    console.log(default_block_content);
    if (default_block_content != '')
        $(this).parents('form').find('.editor_block_content').html(default_block_content);
    return true;

});