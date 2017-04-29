var jQuery = require('jquery');

var openFileBrowser = function (params) {
	var defaults = {
		model: "filebrowser/file", //unused at the moment
		callback: function () {},
		width: 750,
		height: 500,
		name: 'filebrowser',
		filters: 'all'
	};

	jQuery.extend(defaults, params);
	var myWindow = window.open(window.baseURL + "filebrowser/index/index?model=" + defaults.model.replace('/', '-') + "&filters=" + defaults.filters.replace(' ', '').replace(',', '-').replace('/', '_'), defaults.name, 'width=' + defaults.width + ',height=' + defaults.height);
	myWindow.filebrowser_callback = defaults.callback;
	myWindow.filebrowser_model = defaults.model;
	myWindow.filebrowser_filters = defaults.filters;
	return false;
};

(function ($)
{
	$.fn.filebrowser = function (params)
	{




		return this.each(function ()
		{
			$(this).click(function (e) {
				e.preventDefault();
//				console.log(window.baseURL+"/filebrowser/index/index?model="+defaults.model.replace('/','-')+"&filters="+defaults.filters.replace(' ','').replace(',','-').replace('/','_'));
				openFileBrowser(params);
			});
		});


	};

})(jQuery);

module.exports = openFileBrowser;


