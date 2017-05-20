$(function(){
	
	function track($inputs) {
		var inputLen = $inputs.val().length;
		
		var limit = $inputs.data('limit');
		
		var inputLenLabel = $inputs.parent().find('.input-len');
		
		if(limit && inputLen > limit) {
			inputLenLabel.addClass('limit-exceeded');
		} else {
			inputLenLabel.removeClass('limit-exceeded');
		}
		
		inputLenLabel.find('.input-len-num').text(limit ? limit - inputLen : inputLen);
	}
	
	$('.input-tracked').on('input',function(){
		track($(this));
	}).trigger('input');
});


