var $ = window.jQuery || require('jquery');

global.jQuery = $;
require('bootstrap');

$(function () {
	var uls = $('.sidebar-nav > ul > *').clone();
	uls.addClass('visible-xs');
	$('#main-menu').append(uls.clone());
});