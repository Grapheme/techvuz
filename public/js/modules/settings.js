/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
 
$(function(){
	
	$('.module-checkbox').on('change', function(){
		
		var $this = $(this);
		var $value = 0;
		if($(this).is(':checked')){
			$value = 1;
		}
		$.ajax({
			url: $($this).parents('form').attr('action'),
			data: {name: $($this).data('name'), value: $value},
			type: 'post'
		}).done(function(response){
			showMessage.constructor("Настройки",response.responseText);
			showMessage.smallInfo();
		});
	});
	$('.lang-change').on('change', function(){
			var $_form = $(this).parent();
			var $id = $(this).val();
			$.ajax({
				url: $_form.attr('action'),
				data: { id: $id },
				type: 'post',	
			}).done(function(data){
				$.smallBox({
					title : "Settings saved!",
					content : "",
					color : "#296191",
					iconSmall : "fa fa-thumbs-up bounce animated",
					timeout : 4000
				});
			});
		});
});