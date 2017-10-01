var $ = window.jQuery || require('jquery');
var Ps = require('perfect-scrollbar');
//require('perfect-scrollbar/jquery')($);

var perfectScrollBar = function () {

	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

	if (w > 768) {

		var $notifications = $('.notifications');

		if ($notifications.length > 0 && ! $notifications.hasClass('ps-container')) {
			Ps.initialize($notifications[0]);
		}
	}

};

$(window).resize(perfectScrollBar).trigger('resize');

