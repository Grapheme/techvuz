$('.tabs').tabs();
$('.select').selectmenu();

jQuery.fn.notifications = function() {
	'use strict';

	var element = $(this),
		leftBtn = $(this).find('.js-notif-left'),
		rightBtn = $(this).find('.js-notif-right'),
		currentFlag = $(this).find('.notifications-count .current'),
		allFlag = $(this).find('.notifications-count .all'),
		notifListElem = $(this).find('.notifications-li');

	leftBtn.click( function(){
		element.trigger('prev-notification');
	});

	rightBtn.click( function(){
		element.trigger('next-notification');
	});

	element.bind('next-notification', function(){
		var activeElem = notifListElem.filter('.active');
		var activeElemIndex = activeElem.index() + 1;

		notifListElem.removeClass('active');

		if( activeElemIndex < notifListElem.length ) {
			activeElem.next().addClass('active');
		} else {
			notifListElem.first().addClass('active');
		}
		element.trigger('set-count');
	});

	element.bind('prev-notification', function(){
		var activeElem = notifListElem.filter('.active');
		var activeElemIndex = activeElem.index() + 1;

		notifListElem.removeClass('active');

		if( activeElemIndex > 1 ) {
			activeElem.prev().addClass('active');
		} else {
			notifListElem.last().addClass('active');
		}
		element.trigger('set-count');
	});

	element.bind('set-count', function(){
		allFlag.html( notifListElem.length );
		currentFlag.html( notifListElem.filter('.active').index() + 1 );
	});

	//Add active class to the first element of the list
	notifListElem.first().addClass('active');

	element.trigger('set-count');

};
jQuery.fn.accordionPurch = function() {
	'use strict';

	var element = $(this);
	var $mainCheckbox = element.find('.main-checkbox');

	$mainCheckbox.on('change', function(){

		if( $(this).prop('checked') ) {
			$(this).parents('.accordion-body').find('.secondary-checkbox').prop('checked', true);
		} else {
			$(this).parents('.accordion-body').find('.secondary-checkbox').prop('checked', false);
		}

	});
};

//Модуль popup
var Popup = (function(){
	'use strict';

	var $overlay = $('.overlay');
	var $popup = $('.popup');
	var $close = $('.js-popup-close');
	var $forgot = $('.js-forgot-pass');
	var $login = $('.js-login');

	$close.click( function(){
		Popup.close();
	});
	$forgot.click( function(){
		Popup.show('restore');
	});
	$login.click( function(){
		Popup.show('login');
	});
	$popup.click( function(e){
		e.stopPropagation();
	});
	$overlay.click( function(){
		Popup.close();
	});

	return {

		show: function(id){
			$overlay.addClass('active');
			$popup.removeClass('active');
			$('[data-popup="' + id + '"]').addClass('active');
		},

		close: function(){
			$overlay.removeClass('active');
			$popup.removeClass('active');
		}

	};

})();

(function(){
	'use strict';
	var $select = $('.chosen-select');

	$select.chosen({
		no_results_text: 'Ничего не найдено'
    });

    $select.each( function(){
		countPrice( $(this) );
    });

    function countPrice(elem) {

		//Bounded description termin
        var $boundDt = elem.parent().prev();
        //Price-container
        var $price = $boundDt.find('.purchase-price');
        //Price-container text
        var $priceCount = $price.data('price');
        //Listener container
        var $listeners = $boundDt.find('.purchase-listeners');
        //Length of active listeners
        var $listenersLength = elem.find('option:selected').length;
        
        //Function actions
        //1. Fill active listeners
        $listeners.text( $listenersLength );
        //2. Set price
        $price.text( ($listenersLength * $priceCount) ? ($listenersLength * $priceCount) + '.-' : $priceCount + '.-' );
    }

    $('.chosen-select').on('change', function() {

        countPrice( $(this) );

    });
})();

$('.notifications').notifications();
$('.accordion').accordionPurch();
