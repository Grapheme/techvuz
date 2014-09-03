
    $(document).ready(function(){

    	Dropzone.autoDiscover = false;
    	
		var dz_selector = ".egg-dropzone";
		
		if ( $(dz_selector).length ) {

			var myDropzone = new Dropzone(
				dz_selector, {
					url: "/admin/galleries/abstractupload",
		            addRemoveLinks : true,
		            maxFilesize: 0.5,
		            dictResponseError: 'Error uploading file!'
				}
			);

	        myDropzone.on("totaluploadprogress", function(data) {
	            //console.log(data);
	        });

			myDropzone.on("success", function(file, response) {
				//alert(response.image_id);
				$(dz_selector).append("<input type='hidden' name='uploaded_images[]' value='" + response.image_id + "' />");
			});

			myDropzone.on("sending", function(file, xhr, formData) {
				//formData.append("filesize", file.size); // Will send the filesize along with the file as POST data.
				//console.log(file);
				//console.log(xhr);
				//console.log(formData);
				var gallery_id = $('.egg-dropzone').data('gallery_id');
				formData.append("gallery_id", gallery_id);
			});

			myDropzone.on("removedfile", function(file) {
				//console.log();
				// Как-то так...
				var image_id = JSON.parse(file.xhr.response).image_id;
				//alert(image_id);
				deleteUploadedImage(image_id);
				//return false;
				//$(dz_selector).append("<input type='hidden' name='uploaded_images[]' value='" + response.image_id + "' />");
			});

		};


        $('.photo-delete').click(function(){
            var $photoDiv = $(this).parent();
            $.ajax({
                url: "/admin/galleries/photodelete",
                data: { id: $(this).attr('data-photo-id') },
                type: 'post',
            }).done(function(){
                $photoDiv.fadeOut('fast');
            }).fail(function(data){
                console.log(data);
            });           
            return false;
        });

		function deleteUploadedImage(image_id) {
            $.ajax({
                url: "/admin/galleries/photodelete",
                data: { id: image_id },
                type: 'post',
            }).done(function(){
                //$photoDiv.fadeOut('fast');
            }).fail(function(data){
                console.log(data);
            });
            return false;
		}

	});
