var $ = window.jQuery || require('jquery');

require('wysibb');

require('./compiled/homePopup');

var fsc = require('./compiled/full_screen.js');

var openFileBrowser = require('./compiled/filebrowser');

var fullScreen = function(command, value, queryState) {
	fsc.requestFullScreen($(this.$editor)[0]);
	adjustToolbars();
}

var openFile = function (command, value, queryState) {
	var those = this;
	window.document.exitFullscreen();
	openFileBrowser({
		callback: function (file) {

			those.wbbInsertCallback(command, {TYPE: "image", SELTEXT: file.src});
		}
	});
};

var adjustToolbars = function (){
	$('.wysibb-toolbar').each(function(){
		$(this).css('max-height', $(this).parent().css('height'));
	});
}

var codeModal = function (command, opt, queryState) {

	if (queryState) {
		//Delete the current BB code, if it is active.
		//This is necessary if you want to replace the current element
		this.wbbRemoveCallback(command, true);
	}
	
	window.document.exitFullscreen();
	var languages = [
		'java',
		'python',
		'shell',
		'php',
		'html',
		'css',
		'cpp',
		'c#',
		'sql',
		'javascript'
	]
	var form = '<form id="wysibb-code-form">\n\
		<div class="form-group"><label>Language :</label><select class="form-control" name="language">';
	for (var i = 0; i < languages.length; i++) {
		form += '<option value="' + languages[i] + '">' + languages[i] + '</option>';
	}

	form += '</select></div>';
	form += '<div class="form-group"><label>Code : </label>';
	form += '<textarea name="code" class="form-control"></textarea></div>';
	var $form = $(form);

	var those = this;
	var pos = this.getRange();
	
	var $modal = $('#modal-from-dom');
	$modal.modal('show');
	var $removeBtn = $modal.find('.btn-danger');
	$removeBtn.attr('href', '');

	$modal.find(".modal-body").html('').append($form);

	var $header = $modal.find(".modal-header h3");
		
	$header.html('Ajouter du code');
	
	$removeBtn.click(function (e) {
		e.preventDefault();
		$modal.modal('hide');
		$removeBtn.off('click');
		var lang = $form.find('[name="language"]').val();
		var code = $form.find('[name="code"]').val();
		$('#popup-wysibb-code-form').remove();
		those.lastRange = pos;
		those.wbbInsertCallback(command, {LANGUAGE: lang, CODE: code});

		return false;
	});

};

var imgTransform = '<img src="'+window.baseURL+'{SELTEXT}" />';
var zipTransform = '<img class="zip" data-source="{TYPE}" src="'+window.baseURL+'{SELTEXT}"/></div>';

var fileTransformOpt = {};

fileTransformOpt[imgTransform] = '[file=image]{SELTEXT}[/file]';
fileTransformOpt[zipTransform] = '[file=zip]{SELTEXT}[/file]';

var wbbOpt = {
	hotkeys: false, //disable hotkeys (native browser combinations will work)
	showHotkeys: false, //hide combination in the tooltip when you hover.
	lang: "fr",
	traceTextarea: false,
	buttons: 'bold,italic,underline,strike,sup,sub,|,h2,h3,h4,h5,h6,|,warning,keynotion,|,img,video,link,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,code,table,fullscreen',
	allButtons: {
		targetlink: {
			title: 'New page link',
			buttonHTML: '<span class="fonticon ve-tlb-link1">\uE007</span>',
			modal: {
				title: 'modal_link_title',
				width: "500px",
				tabs: [
					{
						input: [
							{param: "SELTEXT", title: CURLANG.modal_link_text, type: "div"},
							{param: "URL", title: CURLANG.modal_link_url, validation: '^http(s)?://'}
						]
					}
				]
			},
			transform: {
				'<a href="{URL}" target="_blank">{SELTEXT}</a>': "[urlblank={URL}]{SELTEXT}[/urlblank]",
				'<a href="{URL}" target="_blank">{URL}</a>': "[urlblank]{URL}[/urlblank]"
			}
		},
		h2: {
			title: 'h2',
			buttonText: 'h2',
			transform: {
				'<h2>{SELTEXT}</h2>': '[h2]{SELTEXT}[/h2]'
			}
		},
		h3: {
			title: 'h3',
			buttonText: 'h3',
			transform: {
				'<h3>{SELTEXT}</h3>': '[h3]{SELTEXT}[/h3]'
			}
		},
		h4: {
			title: 'h4',
			buttonText: 'h4',
			transform: {
				'<h4>{SELTEXT}</h4>': '[h4]{SELTEXT}[/h4]'
			}
		},
		h5: {
			title: 'h5',
			buttonText: 'h5',
			transform: {
				'<h5>{SELTEXT}</h5>': '[h5]{SELTEXT}[/h5]'
			}
		},
		h6: {
			title: 'h6',
			buttonText: 'h6',
			transform: {
				'<h6>{SELTEXT}</h6>': '[h6]{SELTEXT}[/h6]'
			}
		},
		img: {
			title: "Insert your own images !",
			cmd: openFile,
			transform: fileTransformOpt
		},
		code: {
			title: "Insert code snippet",
			buttonText: "code",
			cmd: codeModal,
			transform: {
				'<pre class={LANGUAGE}>{CODE}</pre>': '[code={LANGUAGE}]{CODE}[/code]'
			}
		},
		fullscreen : {
			title: 'Set Wysibb fullscreen',
			buttonText : 'fullscreen',
			cmd : fullScreen
		},
		keynotion : {
			title: 'Key notion',
			buttonText : 'key',
			transform : {
				'<div class="info info-keynotion"><i class="fa fa-key main"></i>{SELTEXT}</div>' : '[keynotion]{SELTEXT}[/keynotion]'
			}
		},
		warning : {
			title: 'Warning',
			buttonText : 'warning',
			transform : {
				'<div class="info info-warning"><i class="fa fa-exclamation-triangle main"></i>{SELTEXT}</div>' : '[warning]{SELTEXT}[/warning]'
			}
		}
	}
}
$(function(){
	$('#blogpost_add_description').wysibb(wbbOpt);
	$('#blogpost_add_content').wysibb(wbbOpt);
//	adjustToolbars();
//	$('.wysibb-toolbar').resize();
	$('.wysibb-text-editor').resize(adjustToolbars).trigger('resize');
	
});

