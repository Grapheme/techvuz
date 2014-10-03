var Courses = (function(){
	var $parent = $('.accordion');
	var $secondaryCheckbox = $parent.find('.secondary-checkbox');
	var $mainCheckbox = $parent.find('.main-checkbox');

	//Загружаем чекнутые боксы on document ready
	$( function(){
		var activeOrders = $.cookie('activeOrders').split(',');

		//Сбрасываем чекбоксы, которые запомнил браузер
		$secondaryCheckbox.prop('checked', false);

		for ( var i=0 ; i < activeOrders.length ; i++ ) {
			$secondaryCheckbox.filter('[value="' + activeOrders[i] + '"]').prop('checked', true);
		}
	});

	//для каждого клика на чекбокс мы должны обновлять массив заказанных курсов

	function renderBuyers(){
		var $parent = $('.accordion-form');
		var $checked = $parent.find('.secondary-checkbox:checked');
		var renderArr = [];

		$checked.each( function(){
			renderArr.push( $(this).val() );
		});

		$.cookie('activeOrders', renderArr);
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

	return {

	};

})();