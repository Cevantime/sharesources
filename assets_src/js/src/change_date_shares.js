var $ = window.jQuery || require('jquery');
var sessionCalendar = require('./calendar');

var locale = window.locale || 'fr';

if(locale === 'en') {
	locale = 'en-gb';
}

require('moment/locale/' + locale);

require('./bootstrap-datetimepicker/bootstrap-datetimepicker');


$(document).ajaxComplete(function () {
	$('.change-share-date').not('.parsed').addClass('parsed').datetimepicker({
		format: 'DD/MM/YYYY',
		icons: {
			time: "fa fa-clock-o",
			date: "fa fa-calendar",
			up: "fa fa-chevron-up",
			down: "fa fa-chevron-down",
			previous: 'fa fa-chevron-left',
			next: 'fa fa-chevron-right',
			today: 'fa fa-screenshot',
			clear: 'fa fa-trash',
			close: 'fa fa-remove',
			inline: true
		}
	}).on('dp.change', function (e) {
		
		$.post(
				window.baseURL + 'courses/changedateshare',
				{
					'csrf_test_name': $(this).closest('form').find('[name^="csrf_"]').val(),
					'date': $(this).val(),
					'course-id': $(this).data('course-id')
				},
				function () {
					sessionCalendar.reload();
				}
		);

	});
});



