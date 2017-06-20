var $ = window.jQuery || require('jquery');

global.jQuery = $;

require('bootstrap');
require('./removeClassRegEx');

function parseModal() {
	$('.confirm').click(function (e) {
		var url = $(this).data('url');
		var body = $(this).data('body');
		var header = $(this).data('header');
		return showModal(header, body, url, 'danger');
	});
}

function showModal(header, body, action, type) {
	var $modal = $('#modal-from-dom');
	$modal.modal('show');
	var $validateBtn = $modal.find('.btn-confirm').removeClassRegEx(function (clazz) {
		return clazz.match(/btn\-.+/) !== null && clazz !== 'btn-confirm';
	}).addClass('btn-'+type);
	if (typeof action === 'string') {
		$validateBtn.attr('href', action);
	} else {
		$validateBtn.click(function (e) {
			e.preventDefault();
			action(e, $modal, $validateBtn);
		});
	}

	var $body = $modal.find(".modal-body");
	$body.html('');
	$body.append(body);

	var $header = $modal.find(".modal-header h3");
	$header.html(header);

	return $modal;
}

$(function () {
	parseModal();
	$(document).ajaxComplete(parseModal);
});

module.exports = {
	parseModal : parseModal,
	showModal : showModal
}