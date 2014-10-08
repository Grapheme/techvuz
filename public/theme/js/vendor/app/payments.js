var Payments = (function(){
	var $deletePayment = $('.js-delete-payment');
	var $editPayment = $('.js-edit-payment');

	//Events
	$deletePayment.click( function(e){
		e.preventDefault();

		var $parent = $(this).parents('tr'),
			$form = $('.form-delete-payment'),
			$id = $parent.data('paymentid'),
			$hidden = $form.find('.delete-payment-id');

		$('#deletePayment').modal().one('click', '#confirmRemove', function (e) {
			$hidden.val( $id );
            $form.trigger('submit');
            $(this).parents('tr').remove();
        });
	});

	$editPayment.click( function(e, callback){
		e.preventDefault();

		//Получаем объект платежа и объект мобального окна редактирования
		var $parent = $(this).parents('tr'),
			$modal = $('.edit-payment-modal'),

			//Получаем данные из таблицы платежа
			$id = $parent.data('paymentid'),
			$date = $.trim( $parent.find('.js-payment-date').text() ),
			$sum = $.trim( parseFloat( $parent.find('.js-payment-price').text() ) ),
			$num = $.trim( $parent.find('.js-payment-id').text() ),

			//Получаем ссылки на поля модального окна
			$idField = $modal.find('.js-edit-payment-id'),
			$dateField = $modal.find('.js-edit-date input'),
			$sumField = $modal.find('.js-edit-sum input'),
			$numField = $modal.find('.js-edit-num input');

			//Заполняем поля модального окна
			$idField.val( $id );
			$dateField.val( $date );
			$sumField.val( $sum );
			$numField.val( $num );

		console.log( $dateField );
		//Открываем модальное окно с готовыми данными
		$('#editPayment').modal();
	});

})();