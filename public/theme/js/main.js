$('.tabs').tabs();

$('.select').selectmenu();
$(function(){
	$('table.sortable').tablesorter();
});

$('.accordion').accordion({
    header: '.accordion-header',
    heightStyle: 'content',
    collapsible: true,
    active: false,

    create: function(event, ui){
		if( window.location.hash ) {
			$(window.location.hash).trigger('click');
		}
    }
});

$('.js-close-notifications').click( function(){
	var self = $(this);
	var url = $(this).data('action');

	var jqxhr = $.ajax( url )
	.done(function() {
		self.parents('.notifications').parent().addClass('hidden');
	})
	.fail(function() {
		
	})
	.always(function() {
		
	});
});

//Test validation
(function(){
	var $testParent = $('.questions-ul');
	var $question = $testParent.find('.questions-li');
	var $testBtn = $testParent.find('li[data-question="finish"] button[type="submit"]');
	var $valid = false;

	//Disable btn at the beginnig
	$testBtn.addClass('btn--disabled');

	//Click on disabled btn do nothing
	$($testBtn).click( function(e) {
		if( $(this).hasClass('btn--disabled') ) {
			e.preventDefault();
			return;
		}
	});

	function validateTest() {
		$valid = true;

		$question.each( function(){
			if( !$(this).find('input[type="radio"]:checked')[0] ) {
				$valid = false;
				return false;
			}
		});

		if($valid) {
			$testBtn.removeClass('btn--disabled');
		} else {
			$testBtn.addClass('btn--disabled');
		}
	}

	$question.find('input[type="radio"]').change( function(){
		validateTest();
	});

})();

//Table search

(function(){
	var $searchForm = $('.employee-search'),
		$searchInput = $searchForm.find('input[type="text"]'),
		$searchTbody = $searchForm.next().find('tbody'),
		$searchError = $('.js-search-table-error');

	$($searchInput).keyup(function(){
        self = this;
        // Show only matching TR, hide rest of them
        $.each($searchTbody.find("tr"), function() {

            if($(this).find('td:first-child').text().toLowerCase().indexOf($(self).val().toLowerCase()) == -1)
                $(this).hide();
            else
                $(this).show();
        });

        if( !$searchTbody.find("tr:visible")[0] ) {
			$searchError.removeClass('hidden');
		} else {
			$searchError.addClass('hidden');
		}

    });
})();

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
		//$('#loginModal').modal();
	});
	$popup.click( function(e){
		e.stopPropagation();
	});
	$overlay.click( function(){
		Popup.close();
	});

	function removeErrors(){
		$('.state-error').removeClass('state-error');
		$('em.invalid').remove();
	}

	return {

		show: function(id){
			$overlay.addClass('active');
			$popup.removeClass('active');
			$('[data-popup="' + id + '"]').addClass('active');
		},

		close: function(){
			$overlay.removeClass('active');
			$popup.removeClass('active');

			//Remove errors from all popup forms
			removeErrors();
		}

	};

})();

(function(){
	'use strict';
	var $select = $('.chosen-select');
	//Кнопка конечной покупки курса
	var $finishBtn = $('.js-coursebuy-finish');
	var $deleteBtn = $('.js-delete-course');

	$select.chosen({
		no_results_text: 'Ничего не найдено'
    });

    $select.each( function(){
		countPrice( $(this) );
		countSum();
    });

    setTimeout( function(){
		getOrderUsers();
	}, 100);

    //Также нам нужна функция, которая восстановит данные о курсах и пользователях при загрузке
    function getOrderUsers(){
		//Достанем JSON из функции
		var orderingObj = $.cookie('ordering') ? JSON.parse( $.cookie('ordering') ) : 'eblya';
		console.log(orderingObj, $.cookie('ordering') );
		var $workTable = '';
		var $workSelect = '';

		console.log( orderingObj );

		//Сбросим все селекты
		$select.find('option:selected').prop('selected', false);

		//И заполним их данными этого объекта
		for (var key in orderingObj) {

			//Находим текущую таблицу
			$workTable = $('.tech-table').filter('[data-courseid="' + key + '"]');

			//И заполняем соседние селекты
			for (var i=0 ; i < orderingObj[key].length ; i++ ){
				
				$workSelect = $workTable.parent().next().find('.chosen-select');
				$workSelect.find('option[value="' + orderingObj[key][i] + '"]').prop('selected', true);
			}

		}

		console.log('finished!');
    }

    function makeCoursesJson(elem) {
		var orderingObj = $.cookie('ordering') ? JSON.parse( $.cookie('ordering') ) : '';
		//Отправляем данные в объект
		//Получим выбранные идентификаторы слушателей
		var $listeners = elem.find('option:selected');
		var $listenersArr = [];

		$listeners.each( function(){
			$listenersArr.push( $(this).val() );
		});
		//Получим ключ курса
		var $parentIndex = elem.parent().prev().find('.tech-table').data('courseid');

		//Заполним объект так: ключ --> массив пользователей
		orderingObj[ $parentIndex ] = $listenersArr;

		$.cookie('ordering', JSON.stringify(orderingObj), { path: '/' });
		
		console.log( $.cookie('ordering') );
    }

    function deleteCourse(btn) {
		var $courseId = btn.parents('.purchase-table').data('courseid');
		var $newOrdering = JSON.parse( $.cookie('ordering'));

		delete $newOrdering[$courseId];

		$.cookie('ordering', JSON.stringify($newOrdering), { path: '/' });

		btn.parents('.purchase-course-dt').add( btn.parents('.purchase-course-dt').next() ).remove();

		countSum();

		if ( !$('.purchase-course-dt').find('.purchase-table')[0] ) {
			window.history.back();
		}
    }

    function countSum() {
		//Sum number
		var $sum = 0;
        //Sum
        var $sumBlock = $('.js-count-sum');
        //Finish sum
        var $sumFinishBlock = $('.js-count-finish-sum');
        //Purchase table
        var $purchaseTable = $('.purchase-table');

        $purchaseTable.each( function(index){
			var $listeners = $(this).find('.purchase-listeners').text();
			var $price = $(this).find('.purchase-price').data('price');
			$sum += $listeners * $price;
			console.log($listeners);
        });

        console.log($sum);

        $sumFinishBlock.text( ($sum + '').replace(/(\d)(?=(\d{3})+$)/g, '$1 ') + '.-' );
    }

    function countPrice(elem) {

		//Bounded description termin
        var $boundDt = elem.parent().prev();
        //Price-container
        var $price = $boundDt.find('.purchase-price');
        // Price-sum-container
        var $priceSum = $boundDt.find('.purchase-price-sum');
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
        $priceSum.text( ($listenersLength * $priceCount) ? ( ($listenersLength * $priceCount) + '' ).replace(/(\d)(?=(\d{3})+$)/g, '$1 ') + '.-' : '0.-' );
    }

    function returnError(text) {
		$('p.error').remove();
		$('.purchase-course-dl').append('<p class="error" style="position: relative; top: -1rem; height: 0; font-size: 14px; color: #bb252d; font-weight: 400;">' + text + '</p>');

		setTimeout( function(){ $('p.error').remove(); }, 3000 );
	}

    $('.chosen-select').on('change', function() {

        countPrice( $(this) );
        makeCoursesJson( $(this) );
        countSum();

    });

    $deleteBtn.click( function(e) {
		deleteCourse( $(this) );
    });

    $finishBtn.click( function(e){
		e.preventDefault();
		//Достанем JSON из функции
		var orderingObj = $.cookie('ordering') ? JSON.parse( $.cookie('ordering') ) : '';
		var finishFlag = true;

		for (var key in orderingObj) {
			if (orderingObj[key].length === 0) {
				finishFlag = false;
			}
		}

		if (!finishFlag) {
			returnError('Пожалуйста, выберите сотрудников для всех курсов');
			return;
		} else {
			$('.purchase-form').submit();
		}

    });

})();

$('.notifications').notifications();
