var $ = window.jQuery || require('jquery');

function parseNotifDelete() {
	$('.notif-mark').not('.parsed').addClass('parsed').click(function(e) {
		e.stopPropagation();
		e.preventDefault();
		var $self = $(this);
		$self.closest('li').addClass('notif-seen');
		var $notification = $('.notification');
		$notification.text(parseInt($notification.text()) - 1);
		$.getJSON(baseURL+'notifications/markasseen/'+$self.data('id'), function(data) {
			if(data.status !== 'ok') {
				$self.closest('li').removeClass('notif-seen');
				$notification.text(parseInt($notification.text()) + 1);
			}
		}).fail(function(){
			$self.closest('li').removeClass('notif-seen');
			$notification.text(parseInt($notification.text()) + 1);
		});

	});
	
}

parseNotifDelete();

$(window).resize(parseNotifDelete);

