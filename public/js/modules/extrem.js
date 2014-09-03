$(function(){
	
	$('.userphoto-checkbox').on('change', function(){
		
		var $this = $(this);
		var $value = 0;
		if($(this).is(':checked')){
			$value = 1;
		}
		$.ajax({
			url: $($this).parents('form').attr('action'),
			data: {photo_id: $($this).data('photo_id'), value: $value},
			type: 'post'
		}).done(function(response){
			showMessage.constructor("Модерация фото", response.responseText);
			showMessage.smallInfo();
		});
	});
});