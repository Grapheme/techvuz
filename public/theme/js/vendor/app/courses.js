var Courses = (function(){
	var $parent = $('.accordion');
	var $secondaryCheckbox = $parent.find('.secondary-checkbox');
	var $mainCheckbox = $parent.find('.main-checkbox');
	var $accBtn = $('.js-btn-acc');

	//Загружаем чекнутые боксы on document ready
	$( function(){
		var ordering = $.cookie('ordering') ? JSON.parse( $.cookie('ordering').split(',') ) : '';

		//Сбрасываем чекбоксы, которые запомнил браузер
		$secondaryCheckbox.prop('checked', false);

		for (var key in ordering) {
			$secondaryCheckbox.filter('[value="' + key + '"]').prop('checked', true);
		}
	});

	//для каждого клика на чекбокс мы должны обновлять массив заказанных курсов

	function renderBuyers(){
		var $parent = $('.accordion-form');
		var $checked = $parent.find('.secondary-checkbox:checked');
		var renderArr = {};

		$checked.each( function(){
			renderArr[ $(this).val() ] = [];
		});

		$.cookie('ordering', JSON.stringify(renderArr));

		if( jQuery.isEmptyObject( renderArr ) || !renderArr ) {
			console.log('empty');
		} else {
			console.log('not empty');
		}
	}

	//События, которые срабатывают при клике на чекбокс
	
	$mainCheckbox.on('change', function(){
		if( $(this).prop('checked') ) {
			$(this).parents('.accordion-body').find('.secondary-checkbox').prop('checked', true);
		} else {
			$(this).parents('.accordion-body').find('.secondary-checkbox').prop('checked', false);
		}
		renderBuyers();
	});

	$secondaryCheckbox.on('change', function(){
		renderBuyers();
	});

})();