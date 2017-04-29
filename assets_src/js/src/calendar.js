var $ = window.jQuery || require('jquery');

var sessionCalendar = {
	
	monthYear : '',
	loaded : false,
	
	loadCalendarForMonth: function (monthYear) {
		if (monthYear === undefined) {
			var date = new Date();
			monthYear = date.getFullYear() + '-' + (date.getMonth() + 1);

		}
		if(this.monthYear) {

			var split = monthYear.split('-');

			var month = split[1];

			if(month < 10) {
				month = '0'+month;
			}

			var monthyearInt = parseInt(split[0]+month);

			var csplit = this.monthYear.split('-');

			var cmonth = csplit[1];

			if(cmonth < 10) {
				cmonth = '0'+cmonth;
			}

			var cmonthyearInt = parseInt(csplit[0]+cmonth);

			if(monthyearInt > cmonthyearInt) {
				var clazz = 'left';
			} else if(monthyearInt < cmonthyearInt) {
				var clazz = 'right';
			} else {
				clazz = 'normal';
			}
		}
		
		this.monthYear = monthYear;
		this.loaded = false;
		var self = this;
		setTimeout(function() {
			if(! self.loaded) {
				$('#calendar').html('<div style="text-align:center;"><div class="loader very-big-loader"></div></div>');
			}
		},300);
		$('#calendar > .datepicker').addClass('inactive '+clazz);
		$('#calendar').load(window.baseURL + 'courses/calendarAjax?monthyear=' + monthYear, function () {
			self.loaded = true;
			$('.prev, .next').click(function () {
				self.loadCalendarForMonth($(this).data('monthyear'));
			});

			$('#calendar .datepicker-days tbody td').click(function () {
				$('.datepicker-days tbody td').removeClass('active');
				$(this).addClass('active');
				self.loadCoursesForDay($(this).data('day'));
			});

		});
	},
	loadCoursesForDay: function (day) {
		if (day === undefined) {
			var date = new Date();
			var day = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
		}
		$('#course-details').load(window.baseURL + 'courses/courseDetails?day=' + day, function() {
			$('.main-panel').animate({
				scrollTop: $("#course-details").offset().top
			}, 'fast');
		});
	},
	
	reload : function() {
		this.loadCalendarForMonth(this.monthYear);
	}
}

$(function () {
	sessionCalendar.loadCalendarForMonth();
});

module.exports = sessionCalendar;

