var $ = window.jQuery || require('jquery');
var Ps = require('perfect-scrollbar');
require('perfect-scrollbar/jquery')($);

var perfectScrollBar = function () {

	var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);

	if (w > 768) {

		var $sidebarWrapper = $('.sidebar-wrapper');
		
//		if( ! $sidebarWrapper.hasClass('ps-container')){
//			Ps.initialize($sidebarWrapper[0], {
//				suppressScrollX: true
//			});
//		}
//		
//		var $mainPanel = $('.main-panel');
//		
//		if( ! $mainPanel.hasClass('ps-container')){
//			Ps.initialize($mainPanel[0]);
//		}

		var $notifications = $('.notifications');

		if ($notifications.length > 0 && ! $notifications.hasClass('ps-container')) {
			console.log('init');
			Ps.initialize($notifications[0]);
		}
	}

};

$(window).resize(perfectScrollBar).trigger('resize');

