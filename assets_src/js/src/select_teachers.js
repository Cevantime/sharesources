var $ = window.jQuery || require('jquery');

require('selectize');

$(function () {

	$('.select-teachers').each(function () {

		var $this = $(this);
		var val = $this.val();
		
		val = val ? val : '[]';
		
		var options = JSON.parse(val);
		
		console.log(options);
		
		var items = [];

		options.forEach(function (opt) {
			items.push(opt.id);
		});

		$this.val('');

		$this.selectize({
			delimiter: ',',
			valueField: 'id',
			searchField: ['name', 'forname'],
			create: false,
			options: options,
			items: items,
			render: {
				option: function (item, escape) {
					return '<div>' +
							'<img class="avatar-sm" src="' + escape(window.baseURL + '/' + (item.avatar ? item.avatar : 'assets/local/images/default-avatar.png')) + '"/>' +
							'<span class="name">' + escape(item.forname) + ' ' + escape(item.name) + '</span>' +
							'</div>';
				},
				item: function (item, escape) {
					return '<div class="item" data-value="' + item.id + '"><img class="avatar-sm" src="' + escape(window.baseURL + '/' +  (item.avatar ? item.avatar : 'assets/local/images/default-avatar.png')) + '"/>' + escape(item.forname) + ' ' + escape(item.name) + '</div>';
				}
			},
			load: function (query, callback) {
				if (!query.length)
					return callback();
				$.ajax({
					url: window.baseURL + 'teachers/search',
					type: 'GET',
					dataType: 'json',
					data: {
						q: query
					},
					error: function () {
						callback();
					},
					success: function (res) {
						callback(res.datas);
					}
				});
			}
		});
	});
});

