var jQuery = require('jquery'),
	datepicker = require('jquery-datepicker'),
	timepicker = require('timepicker');


jQuery( function( $ ) {
	var $datePicker = $('#datepicker');

	if ( ! $datePicker.hasClass('no-hide') ) {
		$datePicker.hide();
	}

	$datePicker.datepicker({
		dateFormat: 'yy-mm-dd',
		altField: "#date-choose"
	});

	$('.button-group input').on('change', function( e ) {
		if ( $(this).hasClass('datepicker-toggle') && $(this).is(':checked') ) {
			$datePicker.show().datepicker('show');
		}
		else {
			$datePicker.hide().val('').datepicker('hide');
		}
	});

	$('.timepicker').timepicker({
		show24: true,
		timeFormat: 'H:i',
		minTime: '08:00',
		maxTime: '22:00'
	});

});
