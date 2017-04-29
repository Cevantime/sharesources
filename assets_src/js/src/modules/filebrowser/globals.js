var $ = require('jquery');

function seeFile(fileId) {
	addWait(fileId);
	$.get(url('filebrowser/index/see/' + fileId), function (html) {
		$('#file-viewer').html(html);
		removeWait(fileId);
	});
}


function fetchFolder(folderId, callback) {
	var $folderEl = $('#folder-' + folderId);
	if (!$folderEl.hasClass('folder-fetched')) {

		addWait(folderId);
		$.get(url('filebrowser/index/seeFolderContent/' + folderId), function (html) {
			$folderEl.append(html);
			$folderEl.addClass('folder-fetched');
			parseNewFiles();
			removeWait(folderId);
			if (callback !== undefined) {
				callback();
			}
		});
	} else {
		if (callback !== undefined) {
			callback();
		}
	}

}


function doAction(name, fileId) {
	switch (name) {
		case 'delete' :
			deleteFile(fileId);
			break;
		case 'add-file' :
			addFile(fileId);
			break;
		case 'add-folder' :
			addFolder(fileId);
			break;
		case 'rename' :
			renameFile(fileId);
			break;
	}
	return false;
}

function addWait(fileId) {
	var $actions = $('.file[data-file="' + fileId + '"] > .file-row .file-actions');
	$actions.append('<div class="loader"></div>');
}

function removeWait(fileId) {
	var $actions = $('.file[data-file="' + fileId + '"] > .file-row .file-actions');
	$actions.find('.loader').remove();
}

function deleteFile(fileId) {
	var $file = $('.file[data-file="' + fileId + '"]');
	addWait(fileId);
	$.getJSON(url('filebrowser/index/delete/' + fileId), function (rep) {
		if (rep.status === 'success') {
			$file.remove();
		}
	});
}

function url(action) {
	return window.baseURL+action+"?model="+window.filebrowser_model.replace('/','-')+"&filters="+window.filebrowser_filters.replace(' ','').replace(',','-').replace('/','_');
}

function renameFile(fileId) {
	var $file = $('.file[data-file="' + fileId + '"]');
	$file.addClass('being-edited');

	var $filename = null;
	var $formToAppend = $('#form-add-folder').clone()
			.css('display', 'inline-block');
	$formToAppend.find('button').text('Valider').click(function(e){
		e.preventDefault();
		$(this).closest('form').submit();
	});
	var $formWrapper = $('<span></span>');
	$formWrapper.append($formToAppend);
	$formWrapper.click(function(e){e.stopPropagation();})
//	$formToAppend.click(function(e){e.stopPropagation();})
	$(document).on('click.lostfocus',function() {
		$filename.html(defaultName);
		$(document).off('click.lostfocus');
	});
	if ($file.hasClass('file-file')) {
		$filename = $file.children('.file-row').find('.file-name')
		$formToAppend.find('input[name="is_folder"]').val(0);
	} else {
		$filename = $file.children('.file-row').find('.folder-name')

	}
//	$formToAppend.click(function(e){e.preventDefault(); return false;});
	var defaultName = $filename.text();
	var $nameField = $formToAppend.find('input[name="name"]');

	$nameField.val(defaultName);
	var $fileParent = $file.parent().parent();
	if ($fileParent.hasClass('file')) {
		$formToAppend.find('input[name="parent_id"]').val($fileParent.data('file'));
	} else {
		$formToAppend.find('input[name="parent_id"]').val(0);
	}
	$formToAppend.append(
		$('<input>').attr('type', 'hidden').attr('name', 'id').attr('value', fileId)
	);
	$formToAppend.submit(function (e) {
		$(document).off('click.lostfocus');
		e.preventDefault();
		if(defaultName === $nameField.val()) {
			$filename.html(defaultName);
			return false;
		}
		addWait(fileId);
		var datas = $formToAppend.serialize();
		$.post(url('filebrowser/index/save'), datas, function (rep) {
			
			if (rep.status === 'success') {
				$filename.html('').append(rep.datas.name);
				$file.removeClass('being-edited');
			}
			removeWait(fileId);
		}, 'json');
		return false;
	});

	$filename.html('').append($formWrapper);

	$nameField.focus();

}

function addFolder(fileId) {
	var $folder = $('.file[data-file="' + fileId + '"]');
	if ($folder.hasClass('being-edited')) {
		return;
	}
	$folder.addClass('being-edited');
	var $fileRow = $('.file[data-file="' + fileId + '"] > .file-row');
	$fileRow.removeClass('displayed selected');
	doSelect($fileRow, function () {
		var $formToAppend = $('#form-add-folder').clone();
		$formToAppend.find('[name="parent_id"]').val(fileId);
		$formToAppend.submit(function (e) {
			e.preventDefault();
			$formToAppend.append('<div class="loader"></div>');
			var datas = $formToAppend.serialize();
			$.post(url('filebrowser/index/add'), datas, function (rep) {
				if (rep.status === 'success') {
					$folder.removeClass('folder-fetched');
					$folder.children('ul').remove();
					fetchFolder(fileId);
				}
				$formToAppend.find('.loader').remove();
				$folder.removeClass('being-edited');
			}, 'json');
			return false;
		});
		$('.file[data-file="' + fileId + '"] > ul').append($('<li>').append($formToAppend));

	});

}
function addFile(fileId) {
	var $folder = $('.file[data-file="' + fileId + '"]');
	if ($folder.hasClass('being-edited')) {
		return;
	}
	$folder.addClass('being-edited');
	var $fileRow = $('.file[data-file="' + fileId + '"] > .file-row');
	$fileRow.removeClass('displayed selected');
	doSelect($fileRow, function () {
		var $formToAppend = $('#form-add-file').clone();
		$formToAppend.find('[name="parent_id"]').val(fileId);
		$formToAppend.submit(function (e) {
			e.preventDefault();
			var datas = new FormData(this);
			$formToAppend.append('<div class="loader"></div>');
			$.ajax({
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				url: url('filebrowser/index/add'),
				type: 'post',
				data: datas,
				success: function (rep) {
					if (rep.status === 'success') {
						$folder.removeClass('folder-fetched');
						$folder.children('ul').remove();
						fetchFolder(fileId);
					}

					$folder.removeClass('being-edited');
					$formToAppend.find('.loader').remove();
				}
			});

			return false;
		});
		$('.file[data-file="' + fileId + '"] > ul').append($('<li>').append($formToAppend));

	});

}

function moveFile(fileId, targetId) {
	var $inputCsrf = $('[name^="csrf_"]');
	var csrfToken = $inputCsrf.val();
	var csrfName = $inputCsrf.attr('name');
	var datas =  {
			id: fileId,
			parent_id: targetId,
	};
	
	datas[csrfName] = csrfToken;
	
	$.ajax({
		url: url('filebrowser/index/save'),
		data: datas,
		dataType: 'json',
		method: 'post',
		success: function (data) {
			if (data.status === 'success') {
				if (targetId === 0) {
					$.get(url('filebrowser/index/seeFolderContent'), function (html) {
						$('#main-folder').html(html);
						parseNewFiles();
					});
					return ;
				}
				var $target = $('.file[data-file="' + targetId + '"]');
				$('.file[data-file="' + fileId + '"]').remove();
				$target.removeClass('folder-fetched');
				$target.children('ul').remove();
				fetchFolder(targetId);
			}
		}
	});
}

function doSelect($fileRow, callback) {
	var $file = $fileRow.parent('.file');
	if ($fileRow.hasClass('selected')) {
		$fileRow.removeClass('selected');
		$file.removeClass('displayed');
		return;
	}
	$file.addClass('displayed');
	var fileId = $file.data('file');
	$('.file-row').removeClass('selected');
	if ($file.hasClass('file-file')) {
		seeFile(fileId);
		$file = $file.parent().parent();

	} else {
		fetchFolder(fileId, callback);
	}

	$fileRow.addClass('selected');

}

var $dragged = null;
var $draggedIn = null;

function parseNewFiles() {

	$('.file-row:not(.parsed)').addClass('parsed').click(function (e) {
		e.preventDefault();
		doSelect($(this));
	}).on({
		dragstart: function (e) {
			$dragged = $(this).parent('.file');
		},
		dragend: function (e, ui) {
			if ($dragged === null)
				return true;
			e.preventDefault();
			var $file = $dragged;
			if ($draggedIn === null) {
				moveFile($file.data('file'), 0);
			} else {
				var $target = $draggedIn.parent();
				if ($target.hasClass('file-file')) {
					$target = $target.parent().parent();
				}
				moveFile($file.data('file'), $target.data('file'));

			}
			$draggedIn = null;
			$dragged = null;
		},
		dragover: function (e) {
			$draggedIn = $(this);
			e.preventDefault();
		},
		dragleave: function (e) {
			$draggedIn = null;
		}
	});

	$('.file.file-file:not(.parsed)').addClass('parsed').dblclick(function () {
		onSelect($(this));
	});

	$('.action:not(.parsed)').addClass('parsed').click(function (e) {
		e.preventDefault();
		doAction($(this).data('action'), $(this).data('file'));
		return false;
	});

}

function submitFolder($formFolder, e) {
	e.preventDefault();
	var datas = $formFolder.serialize();
	$formFolder.append('<div class="loader"></div>');
	$.post(url('filebrowser/index/add'), datas, function (rep) {
		if (rep.status === 'success') {
			$.get(url('filebrowser/index/seeFolderContent'), function (html) {
				$('#main-folder').html(html);
				parseNewFiles();
				$formFolder.remove();
			});
		}
	}, 'json');
}

function submitFile($formFile, e) {
	e.preventDefault();
	var datas = new FormData($formFile[0]);
	$formFile.append('<div class="loader"></div>');
	$.ajax({
		async: false,
		cache: false,
		contentType: false,
		processData: false,
		dataType: 'json',
		url: url('filebrowser/index/add'),
		type: 'post',
		data: datas,
		success: function (rep) {
			if (rep.status === 'success') {
				$.get(url('filebrowser/index/seeFolderContent'), function (html) {
					$('#main-folder').html(html);
					parseNewFiles();
					$formFile.remove();
				});
			}
			$formFile.find('.loader').remove();
		}
	});
}

function initForms() {
	var $formFolder = $('#form-add-folder');

	var $formFile = $('#form-add-file');

	$('h2 [data-action="add-folder"]').click(function (e) {
		e.preventDefault();
		var $clone = $formFolder.clone();
		$clone.submit(function (e) {
			submitFolder($clone, e);
		}).prependTo('#file-browser');
	});
	
	$('h2 [data-action="add-file"]').click(function (e) {
		e.preventDefault();
		var $clone = $formFile.clone();
		$clone.submit(function (e) {
			submitFile($clone, e);
		}).prependTo('#file-browser');
	});

	$("#select-bt").click(function () {
		var $fileSelected = $('.file-row.selected').parent();
		if ($fileSelected.hasClass('file-file')) {
			onSelect($fileSelected);
		}
	});
}
function onSelect($elm) {
	var id = $elm.data('file');
	var src = $elm.data('source');
	var type = $elm.data('type');
	var infos = $elm.data('infos');
	var obj = {
		id: id,
		src: src,
		type: type,
		infos: infos
	};
	
	filebrowser_callback(obj);
	window.close();
}

$(function () {
	parseNewFiles();
	initForms();

});