/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
var BASIC = BASIC || {};

BASIC.baseURL = window.location.protocol+'//'+window.location.hostname+'/';
BASIC.currentURL = window.location.href;
BASIC.inputChanged = true;

BASIC.isValidEmailAddress = function(emailAddress){
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	if(emailAddress != ''){return pattern.test(emailAddress);}
	return true;
};
BASIC.isValidPhone = function(phoneNumber){
	var pattern = new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i);
	if(phoneNumber == ''){
		return false;
	}else{
		return pattern.test(phoneNumber);
	}
};
BASIC.getBaseURL = function(url){
	return BASIC.baseURL+url;
}

BASIC.RedirectTO = function(path){window.location=path;}
BASIC.minLength = function(string,Len){if(string != ''){if(string.length < Len){return false}}return true}

$.fn.elementDisabled = function(disabled){
	if($(this).find('.btn-response-text').length > 0){
		if($(this).attr('data-temporary-title') == undefined){
			var btnTitle = $(this).find('.btn-response-text').html().trim();
			$(this).attr('data-temporary-title',btnTitle);
		}
	}
	if(disabled === true){
		$(this).attr('disabled','disabled').find('i.fa-spinner').removeClass('hidden');
		$(this).find('.btn-response-text').html('ОЖИДАЙТЕ');
	}else{
		$(this).removeAttr('disabled').find('i.fa-spinner').addClass('hidden');
		if($(this).attr('data-temporary-title') != undefined){
			var btnTitle = $(this).attr('data-temporary-title');
			$(this).removeAttr('data-temporary-title');
			$(this).find('.btn-response-text').html(btnTitle);
		}
	}
}

$.fn.exists = function(){
	if($(this).length > 0){
		return true;
	}else{
		return false;
	}
}
$.fn.emptyValue = function(){
	if($(this).val().trim() == ''){
		return true;
	}else{
		return false;
	}
}

$(function(){
	$(".btn-spinner").mouseover(function(){$(this).find('i').eq(0).removeClass('hidden');});
	$(".btn-spinner").mouseout(function(){$(this).find('i').eq(0).addClass('hidden');})
});


// http://www.mrclay.org/2010/11/14/using-jquery-before-its-loaded/
//defer$();
/*
    $(document).ready(function() {
        if (defer)
            defer();
    });
*/
/*-------------------------------------------------------------------*/
/*-------------------------------------------------------------------*/
/*-------------------------------------------------------------------*/
/*
    $(document).ready(function() {
    	
        var myDropzone = new Dropzone("#mydropzone", {
            addRemoveLinks : false,
            maxFilesize: 0.5,
            dictResponseError: 'Error uploading file!'
        });

        //myDropzone.on("totaluploadprogress", function(data) {
        //    console.log(data);
        //});

        $('.photo-delete').click(function(){
            var $photoDiv = $(this).parent();
            $.ajax({
                url: "{{slink::createLink('admin/galleries/photodelete')}}",
                data: { id: $(this).attr('data-photo-id') },
                type: 'post',
            }).done(function(){
                $photoDiv.fadeOut('fast');
            }).fail(function(data){
                console.log(data);
            });           
            return false;
        });
    });
*/
/*-------------------------------------------------------------------*/
/*-------------------------------------------------------------------*/
/*-------------------------------------------------------------------*/