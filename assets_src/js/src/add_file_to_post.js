var $ = window.jQuery || require('jquery');
$(function(){
	
	$('.do-close').click(function (e) {
		e.preventDefault();
		$(this).parent().remove();
	});
	var openFileBrowser = require('./compiled/filebrowser');

	$('#add-files').click(function (e) {
		e.preventDefault();
		openFileBrowser({
			callback: function (file) {
				console.log(file);
				var $toAppend = $('<div><input type="hidden" name="files[]" value="' + file.id + '"/> \n\
	<a class="do-close" href="#"><i class="fa fa-close"></i></a> <i class="fa fa-file"></i> ' + file.infos.name + '</div>');
				$toAppend.insertAfter($('#add-files'));
				$toAppend.find('.do-close').click(function (e) {
					e.preventDefault();
					$toAppend.remove();
				});
			}
		});
	});

});
