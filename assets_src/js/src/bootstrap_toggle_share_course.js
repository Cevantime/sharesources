var $ = window.jQuery || require('jquery');

var parseToggle = require('./bootstrap_toggle_generic');


function parse() {
	parseToggle('.toggle-share-course-session','courses/requestshare', 'courses/requestunshare', 'course-share-id' );
};

$(function(){
	
	parse();
	var table = $('.datatabled').DataTable();
	table.on('draw.dt', parse);
	
});

