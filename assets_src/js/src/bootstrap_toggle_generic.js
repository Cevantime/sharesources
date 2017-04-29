var $ = window.jQuery || require('jquery');

var parseToggle = function(clazz, uriDo, uriUndo, dataName) {
	$(clazz).not('.toggled').addClass('toggled').change(function(){
		var $self = $(this);
		var share = $self.prop('checked');
		var dataId = $self.data(dataName);
		if(share) {
			$.getJSON(window.baseURL+uriDo+'/'+dataId, function(data){
				if(data.status !== 'ok') {
					toggle($self, 'off');
				} else {
					$self.closest('tr').find('.td-actions').html(data.html);
				}
			}).fail(function(){
				toggle($self, 'off');
			});
		} else {
			$.getJSON(window.baseURL+uriUndo+'/'+dataId, function(data){
				if(data.status !== 'ok') {
					toggle($self, 'on');
				} else {
					$self.closest('tr').find('.td-actions').html(data.html);
				}
			}).fail(function(){
				toggle($self, 'on');
			});
		}
		
		function toggle($self, mode) {
			$self.data('no-toggle', mode);
		}
	});
	
};

module.exports = parseToggle;