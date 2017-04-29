var $ = window.jQuery || require('jquery');

global.jQuery = $;

var locale = window.locale || 'fr';

require('moment/locale/'+locale);

require('./bootstrap-datetimepicker/bootstrap-datetimepicker');

$(function() {
	function parseDatePicker() {
		$('.datepicker-field').datetimepicker({
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
         });
	}
	
	parseDatePicker();
	
	$( document ).ajaxComplete(parseDatePicker);
});


