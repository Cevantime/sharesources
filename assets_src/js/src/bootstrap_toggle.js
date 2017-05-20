var $ = window.jQuery || require('jquery');

var parseToggle = require('./bootstrap_toggle_generic');

function parse() {
	parseToggle('.toggle-session-teacher-share','sessions/requestshare', 'sessions/requestunshare', 'teach-session-id' );
}

$(function(){
	
	parse();
	var table = $('.datatabled').DataTable();
	table.on('draw.dt', parse);
	
});