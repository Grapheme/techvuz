
$(document).ready(function() {

    $("#reserve-form").validate({
        rules: {
            room_type: "required",
            date_start: "required",
            date_stop: "required",
            name: "required",
            contact: "required"
        },
        messages: {
            room_type: "",
            date_start: "",
            date_stop: "",
            name: "",
            contact: ""
        },
        errorClass: "inp-error",
        submitHandler: function(form) {
            //console.log(form);
            sendReserveForm(form);
            return false;
        }
    });
    
    function sendReserveForm(form) {
    
        //console.log(form);
    
        var options = { target: null, type: $(form).attr('method'), dataType: 'json' };
    
        options.beforeSubmit = function(formData, jqForm, options){
            $(form).find('button').addClass('loading');
            //$('.error').text('').hide();
        }
    
        options.success = function(response, status, xhr, jqForm){
            //console.log(response);
            //$('.success').hide().removeClass('hidden').slideDown();
            //$(form).slideUp();
    
            if (response.status) {
                //$('.error').text('').hide();
                //location.href = response.redirect;

                //$('.response').text(response.responseText).slideDown();
                //$(form).slideUp();

        		$('.form-success').addClass('active');
        
        		setTimeout( function(){
        			$('.booking-form').removeClass('active');
        			$('.form-success').removeClass('active');
        		}, 2500);

            } else {
                //$('.response').text(response.responseText).show();
            }
    
        }
    
        options.error = function(xhr, textStatus, errorThrown){
            console.log(xhr);
        }
    
        options.complete = function(data, textStatus, jqXHR){
            $(form).find('button').removeClass('loading');
        }
    
        $(form).ajaxSubmit(options);
    }

});
