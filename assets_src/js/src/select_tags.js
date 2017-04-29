var $ = window.jQuery || require('jquery');

require('selectize');

$(function(){
	
	$('.to-selectize').selectize({
		delimiter: ',',
		valueField: 'label',
		labelField: 'label',
		searchField: 'label',
		create: true,
		persist: true,
		render: {
			option: function (item, escape) {
				return '<div>' +

						'<span class="name">' + escape(item.label) + '</span>' +

						'</div>';
			}
		},
		load: function (query, callback) {
			if (!query.length)
				return callback();
			$.ajax({
				url: window.baseURL + 'tags/all',
				type: 'GET',
				dataType: 'json',
				data: {
					search: query
				},
				error: function () {
					callback();
				},
				success: function (res) {
					callback(res.tags);
				}
			});
		}
	});

});

