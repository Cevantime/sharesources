var $ = window.jQuery || require('jquery');
global.jQuery = $;
global.$ = $;

require('bootstrap');

$('#minimizeSidebar').click(function(){
	var miniActive = typeof md.misc.sidebar_mini_active === 'undefined' || ! md.misc.sidebar_mini_active;
	$.ajax(window.baseURL+'userprefs/sidebar_collapsed/'+(miniActive ? 1 : 0));
});

if(hljs !== 'undefined'){
  
  $(document).ready(function() {
    $('code.inline').each(function(i, block) {
      hljs.highlightBlock(block);
    });
  });
}