var $ = window.jQuery || require('jquery');

$(function() {
	
	$('.session-select').click(function(e){
		e.stopPropagation();
	});
	$('.session-select').change(function() {
		var redirect = window.currentURL || 'home';
		window.location = window.baseURL+'sessions/setcurrent/'+$(this).val()+'?redirect='+encodeURIComponent(redirect);
	});
});

