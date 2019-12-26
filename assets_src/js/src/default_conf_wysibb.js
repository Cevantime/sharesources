var $ = window.jQuery || require('jquery');

require('wysibb');

require('./compiled/homePopup');

var openFileBrowser = require('./compiled/filebrowser');

var openFile = function (command, value, queryState) {
	var those = this;
	openFileBrowser({
		callback: function (file) {

			those.wbbInsertCallback(command, {TYPE: "zip", SELTEXT: window.baseURL + file.src});
		}
	});
};

var codeModal = function (command, opt, queryState) {

	if (queryState) {
		//Delete the current BB code, if it is active.
		//This is necessary if you want to replace the current element
		this.wbbRemoveCallback(command, true);
	}

	var languages = [
		'java',
		'python',
		'php',
		'html',
		'css',
		'cpp',
		'c#',
		'sql',
		'javascript'
	];
	var form = '<form id="wysibb-code-form">\n\
		<div class="row"><label>Language :</label><select name="language">';
	for (var i = 0; i < languages.length; i++) {
		form += '<option value="' + languages[i] + '">' + languages[i] + '</option>';
	}

	form += '</select></div>';
	form += '<div class="row"><label> Code : </label>';
	form += '<textarea name="code"></textarea></div>';
	form += '<div class="row"><button type="submit">Valider</button></div>';
	var $form = $(form);

	var those = this;
	var pos = this.getRange();
	$form.popup({
		closeButton: '<span class="close-bt"></span>'
	});
	
	$('#popup-wysibb-code-form form').submit(function (e) {
		e.preventDefault();
		var lang = $(this).find('[name="language"]').val();
		var code = $(this).find('[name="code"]').val();
		$('#popup-wysibb-code-form').remove();
		those.lastRange = pos;
		those.wbbInsertCallback(command, {LANGUAGE: lang, CODE: code});

		return false;
	});

}

var wbbOpt = {
	hotkeys: false, //disable hotkeys (native browser combinations will work)
	showHotkeys: false, //hide combination in the tooltip when you hover.
	lang: "fr",
	buttons: 'bold,italic,underline,strike,sup,sub,|,h2,h3,h4,h5,h6,|,img,video,link,|,bullist,numlist,|,fontcolor,fontsize,fontfamily,|,justifyleft,justifycenter,justifyright,|,code,table,removeFormat',
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
			transform: {
				'<img src="{SELTEXT}" />': '[file=image]{SELTEXT}[/file]',
				'<img class="zip" data-source="{TYPE}" src="{SELTEXT}"/></div>': '[file=zip]{SELTEXT}[/file]'
			}
		},
		code: {
			title: "Insert code snippet",
			buttonText: "code",
			cmd: codeModal,
			transform: {
				'<pre class={LANGUAGE}>{CODE}</pre>': '[code={LANGUAGE}]{CODE}[/code]'
			}
		}
	}
}
$(function(){
	$('#wysibb').wysibb(wbbOpt);
});

