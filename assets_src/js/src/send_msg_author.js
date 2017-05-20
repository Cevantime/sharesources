var $ = window.jQuery || require('jquery');

var showModal = require('./modules/bo/parseModal').showModal;

$('.send-msg').click(function(e){
	e.preventDefault();
	var form = $('#form-msg-wrapper').find('form').clone();
	
	showModal('Envoyer un commentaire ou signaler une erreur', form, function(){
		form.submit();
	},'success');
});
