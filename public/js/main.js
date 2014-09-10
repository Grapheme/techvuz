var App = (function() {
	var $window = $(window);
	var $header = $('.main-header');
	var $footer = $('.main-footer');
	var $slideshow = $('#slideshow');
	var $main = $('main');
	var bookForm = $('.booking-form');

	//Events for booking form

	$('#bookBtn').click( function(e){
		e.stopPropagation();
		bookForm.toggleClass('active');
	});

	bookForm.click( function(e){
		e.stopPropagation();
	});

	$('.booking-form .btn1').click( function(){
		$('.form-success').addClass('active');

		setTimeout( function(){
			bookForm.removeClass('active');
			$('.form-success').removeClass('active');
		}, 2500);
		
	});

	$('dt.link-imgs-li').click( function(){
		if( $(this).next().hasClass('active') ) {
			$(this).next().removeClass('active');
		} else {
			$('.link-imgs-dd').removeClass('active');
			$(this).next().addClass('active');
		}
	});

	//Events for language

	$('.lang-li').click( function(){
		$('.lang-li').removeClass('active');
		$(this).addClass('active');
	});

	return {
		//If slider is only child of main element
		//Calculate it's height as window 100% height - (header + footer) height
		//Else we calculate 100% - header heigth, also adding the static-footer class to main footer
		footerInit: function() {
			var $window = $(window);
			var $footer = $('.main-footer');
			var $main = $('main');

			if($main.height() < $window.height()){
				$footer.removeClass('static-footer');
			}
		},
		slideshowInit: function() {
			$slideshow.height( $window.height() - $header.height() );
			$footer.addClass('static-footer');
		},
		init: function() {
			if ($main.children().length == 1 && $main.children().filter('.slideshow')[0]) {
				$main.addClass('full-screen');
			} else {
				this.slideshowInit();

				//Align slider on resize
				$(window).resize( function() {
					App.slideshowInit();
					App.footerInit();
				});
			}

			$(window).load(function() {
				$('body').addClass('loaded');
			});

			this.footerInit();
		}
	};
})();

jQuery.fn.slideshow = function(obj) {
	var element = $(this),
		arrows = $(this).find('.arrow'),
		arrowLeft = $(this).find('.arrow-left'),
		arrowRight = $(this).find('.arrow-right'),
		slides = $(this).find('.slide'),
		activeSlide = 0;

	//Убираем стрелки, если количество слайдов <= 1
	if( slides.length <= 1 ) {
		arrows.hide();
	}

	arrowLeft.click( function(){
		element.trigger('slideshow.prev');
	});

	arrowRight.click( function(){
		element.trigger('slideshow.next');
	});

	//Slider events
	//Previous slide
	element.bind('slideshow.prev', function(e){
		var prevIndex = 0;

		slides.filter('.active').removeClass('active');

		if( activeSlide > 0 ) {
			prevIndex = --activeSlide;
		} else {
			prevIndex = activeSlide = slides.length - 1;
		}

		slides.eq(prevIndex).addClass('active');
	});

	//Next slider
	element.bind('slideshow.next', function(e){
		var nextIndex = 0;

		slides.filter('.active').removeClass('active');

		if( activeSlide < (slides.length - 1) ) {
			nextIndex = ++activeSlide;
		} else {
			nextIndex = activeSlide = 0;
		}

		slides.eq(nextIndex).addClass('active');
	});

	//Method show
	element.bind('slideshow.show', function(e, num){
		slides.filter('.active').removeClass('active');
		slides.eq(num).addClass('active');

		console.log('show');
	});

	//Show first slide at the beginning
	element.trigger('slideshow.show', activeSlide);
	if(obj.autoplay === true) {
		console.log('loop');
		var timer = setTimeout(function autoplay() {
			element.trigger('slideshow.next');
			timer = setTimeout(autoplay, 6000);
		}, 6000);
	}
};

jQuery.fn.tabs = function(control) {
	var element = $(this);
	control = $(control);

	element.delegate('li > span', 'click', function(){
		//Извлечение имени вкладки
		var tabName = $(this).parent().data('tab');

		//Запуск пользовательского события при щелчке на вкладке
		element.trigger("change.tabs", tabName);
	});

	//Привязка к пользовательскому событию
	element.bind('change.tabs', function(e, tabName){
		element.find('li > span').parent().removeClass('active');
		element.find('>[data-tab="' + tabName + '"]').addClass('active');
	});

	element.bind('change.tabs', function(e, tabName) {
		control.find('>[data-tab]').removeClass("active");
		control.find('>[data-tab="' + tabName + '"]').addClass("active");
	});

	$('#tabs').bind('change.tabs', function(e, tabName) {
		window.location.hash = tabName;
	});

	$(window).bind('hashchange', function(){
		var tabName = window.location.hash.slice(1);
		$('#tabs').trigger('change.tabs', tabName);
	});

	//Активация первой вкладки
	var garant = element.find('li:first').attr('data-tab');
	element.trigger('change.tabs', garant);
	return this;
};

$('.slideshow').slideshow({
	autoplay: false
});
$("ul#tabs").tabs("#tabContent");
App.init();

    $(document).on('click', '.reserve_room', function() {
        var room_id = $(this).data('room_id');
        $('select[name=room_type] option[value=' + room_id + ']').attr('selected', 'yes').val(room_id).change();
        $('.booking-form').addClass('active');
        $('html, body').animate({
            scrollTop: $('html').offset().top
        }, 300);
    });