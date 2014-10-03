$('.tabs').tabs();
$('.select').selectmenu();

$('.accordion').accordion({
    header: '.accordion-header',
    heightStyle: 'content',
    collapsible: true
});

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

(function(){
	'use strict';
	var $select = $('.chosen-select');
	//Кнопка конечной покупки курса
	var $finishBtn = $('.js-coursebuy-finish');

	//Получим из куков массив курсов
	var activeOrders = $.cookie('activeOrders') ? $.cookie('activeOrders').split(',') : '';

	//Собираем объект курсы - слушатели
	var $courseObj = {};
	for( var i=0 ; i < $coursesArr.length ; i++ ) {
		$courseObj[ $coursesArr[i] ] = '';
	}

	$select.chosen({
		no_results_text: 'Ничего не найдено'
    });

    $select.each( function(){
		countPrice( $(this) );
    });

    //Также нам нужна функция, которая восстановит данные о курсах и пользователях при загрузке
    $( function(){
		//Достанем JSON из функции
		var orderingObj = $.cookie('ordering') ? JSON.parse( $.cookie('ordering') ) : '';
		var $workTable = '';
		var $workSelect = '';

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

		console.log( orderingObj );
    });

    function makeCoursesJson(elem) {
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
		$courseObj[ $parentIndex ] = $listenersArr;

		$.cookie('ordering', JSON.stringify($courseObj));
		console.log( $.cookie('ordering') );
    }

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

    function returnError(text) {
		$('.purchase-course-dl').after('<p class="error" style="font-size: 14px; color: #bb252d; font-weight: 400;">' + text + '</p>');

		setTimeout( function(){ $('p.error').remove(); }, 3000 );
	}

    $('.chosen-select').on('change', function() {

        countPrice( $(this) );
        makeCoursesJson( $(this) );

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
			returnError('Слушатели выбраны не для всех курсов в списке');
			return;
		}

    });

})();

$('.notifications').notifications();
