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

$('.notifications').notifications();