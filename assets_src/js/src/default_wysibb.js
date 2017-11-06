var $ = window.jQuery || require('jquery');

require('wysibb');

require('./compiled/homePopup');

require('./fullscreen');

require('selectize');

var fsc = require('./compiled/full_screen.js');

var openFileBrowser = require('./compiled/filebrowser');

var showModal = require('./modules/bo/parseModal').showModal;

var fullScreen = function (command, value, queryState) {
    fsc.requestFullScreen($(this.$editor)[0]);
    adjustToolbars();
}

var openFile = function (command, value, queryState) {
    var those = this;
    var pos = this.getRange();
    var isFullScreen = window.document.isFullScreen();
    if (isFullScreen) {
        window.document.exitFullscreen();
    }
    openFileBrowser({
        callback: function (file) {

            var fileType = file.infos.type.split('/')[1];

            switch (fileType) {
                case 'zip':
                    those.wbbInsertCallback(command, {NAME: file.infos.name, SRCZIP: encodeURI(file.src)});
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':

                    var formImgTypeBody = '<form>'
                            + '<div class="form-group">'
                            + '<label>Comment souhaitez-vous insérer votre image ?</label><br>'
                            + '<input type="radio" value="normal" name="imgDisplay" id="normalDisplay" checked> '
                            + '<label for="blockDisplay">Normalement</label><br>'
                            + '<input type="radio" value="left" name="imgDisplay" id="leftDisplay"> '
                            + '<label for="leftDisplay">Flottante à gauche</label><br>'
                            + '<input type="radio" value="right" name="imgDisplay" id="rightDisplay"> '
                            + '<label for="rightDisplay">Flottante à droite</label><br>'
                            + '</div>'
                            + '<div class="form-group">'
                            + '<label>Déscription de l\'image</label><br>'
                            + '<textarea class="form-control" name="description" id="description"></textarea><br>'
                            + '</div>'
                            + '</form>';
                    var $form = $(formImgTypeBody);
                    var action = function (e, $modal, $validateBtn) {
                        $modal.modal('hide');
                        $validateBtn.off('click');
                        those.lastRange = pos;
                        var description = $('#description').val();
                        if ($('#normalDisplay').is(':checked')) {
                            those.wbbInsertCallback(command, {NAME: description ? description : file.infos.name, SRCIMG: encodeURI(file.src)});
                        } else if ($('#leftDisplay').is(':checked')) {
                            those.wbbInsertCallback(command, {NAME: description ? description : file.infos.name, SRCIMGLEFT: encodeURI(file.src)});
                        } else {
                            those.wbbInsertCallback(command, {NAME: description ? description : file.infos.name, SRCIMGRIGHT: encodeURI(file.src)});
                        }

                        if (isFullScreen) {
                            fsc.requestFullScreen($(those.$editor)[0]);
                            adjustToolbars();
                        }

                        return false;
                    };
                    showModal('Affichage de l\'image', $form, action, 'success');
                    break;
            }
        }
    });
};

var adjustToolbars = function () {
    $('.wysibb-toolbar').each(function () {
        $(this).css('max-height', $(this).parent().css('height'));
    });
}

var codeModal = function (command, opt, queryState) {

    if (queryState) {
        //Delete the current BB code, if it is active.
        //This is necessary if you want to replace the current element
        this.wbbRemoveCallback(command, true);
    }

    var isFullScreen = window.document.isFullScreen();

    if (isFullScreen) {
        window.document.exitFullscreen();
    }

    var languages = {
        'php': 'PHP',
        'html': 'HTML',
        'css': 'CSS',
        'javascript': 'JavaScript',
        'bash': 'Bash',
        'java': 'Java',
        'python': 'Python',
        'cpp': 'C++',
        'c#': 'C#',
        'sql': 'SQL'
    }
    var form = '<form id="wysibb-code-form">\n\
		<div class="form-group"><label>Language :</label><select class="form-control" name="language">';

    for (var language in languages) {
        form += '<option value="' + language + '">' + languages[language] + '</option>';
    }

    form += '</select></div>';
    form += '<div class="form-group"><label>Code : </label>';
    form += '<textarea name="code" class="form-control" rows="7"></textarea></div>';
    var $form = $(form);

    var those = this;
    var pos = this.getRange();

    var action = function (e, $modal, $validateBtn) {
        $modal.modal('hide');
        $validateBtn.off('click');
        var lang = $form.find('[name="language"]').val();
        var code = $form.find('[name="code"]').val();
        // encoding html if any
        var div = document.createElement('div');
        var text = document.createTextNode(code);
        div.appendChild(text);
        code = div.innerHTML;
        those.lastRange = pos;
        those.wbbInsertCallback(command, {LANGUAGE: lang, CODE: code});
        if (isFullScreen) {
            fsc.requestFullScreen($(those.$editor)[0]);
            adjustToolbars();
        }
        return false;
    };

    showModal('Ajouter du code', $form, action, 'danger');


};
var addLink = function (command, opt, queryState) {

    var defaultVal = this.getSelectText();

    if (queryState) {
        //Delete the current BB code, if it is active.
        //This is necessary if you want to replace the current element
        this.wbbRemoveCallback(command, true);
    }

    var isFullScreen = window.document.isFullScreen();

    if (isFullScreen) {
        window.document.exitFullscreen();
    }

    var options = {
        course: 'Lien vers un cours',
        external: 'Lien externe'
    };

    var formBody = '<div class="form-group">';

    formBody += '<label>Type de lien</label>';

    formBody += '<select class="form-control" name="select-link-type">';

    formBody += '<option value=""></option>';

    for (opt in options) {
        formBody += '<option value="' + opt + '">' + options[opt] + '</option>';
    }

    formBody += '</select>';

    formBody += '</div>';

    formBody += '<div class="form-group label-link-group">';

    formBody += '<label>Texte du lien</label>';

    formBody += '<input type="text" class="form-control" value="' + defaultVal + '" name="label-link"/>';

    formBody += '</div>';

    formBody += '<div class="form-group external-link-group">';

    formBody += '<label>Url du lien</label>';

    formBody += '<input type="text" class="form-control" name="external-link-url"/>';

    formBody += '</div>';

    formBody += '<div class="form-group course-link-group">';

    formBody += '<label>Nom cu cours (à sélectionner)</label>';

    formBody += '</div>';

    var $form = $('<form>' + formBody + '</form>');

    var $selectCourse = $('<select name="select-link-course"></select>');

    var those = this;

    var pos = this.getRange();

    var action = function (e, $modal, $validateBtn) {
        var linkType = $form.find('[name="select-link-type"]').val();
        if (linkType === 'external') {
            var linkText = $form.find('[name="label-link"]').val();
            var linkUrl = $('[name="external-link-url"]').val();
            if (linkText !== '' && linkUrl !== '') {
                $modal.modal('hide');
                $validateBtn.off('click');
                those.lastRange = pos;
                those.wbbInsertCallback(command, {URL: linkUrl, SELTEXT: linkText});
            }
        } else if (linkType === 'course') {
            var linkText = $form.find('[name="label-link"]').val();
            var linkId = $('[name="select-link-course"]').val();
            if (linkText !== '' && linkUrl !== '') {
                $modal.modal('hide');
                $validateBtn.off('click');
                those.lastRange = pos;
                those.wbbInsertCallback(command, {ID: linkId, SELTEXT: linkText});
            }
        }

        if (isFullScreen) {
            fsc.requestFullScreen($(those.$editor)[0]);
            adjustToolbars();
        }
    }

    showModal('Entrer votre lien', $form, action, 'success');

    $form.find('.course-link-group').append($selectCourse);

    $form.find('[name="select-link-type"]').change(function () {
        var linkType = $(this).val();
        if (linkType === 'external') {
            $form.find('.external-link-group').show();
            $form.find('.course-link-group').hide();
        } else if (linkType === 'course') {
            $form.find('.external-link-group').hide();
            $form.find('.course-link-group').show();
        }
    });

    $selectCourse.selectize({
        valueField: 'id',
        create: false,
        labelField: 'title',
        searchField: 'title',
        load: function (query, callback) {
            if (!query.length)
                return callback();
            $.ajax({
                url: window.baseURL + 'courses/search',
                type: 'GET',
                dataType: 'json',
                data: {
                    q: query
                },
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res.datas);
                }
            });
        }
    });

    $form.find('.external-link-group').hide();
    $form.find('.course-link-group').hide();


};

var imgTransform = '<img src="' + window.baseURL + '{SRCIMG}" alt="{NAME}"/>';
var imgLeftTransform = '<img class="image-left" src="' + window.baseURL + '{SRCIMGLEFT}" alt="{NAME}"/>';
var imgRightTransform = '<img class="image-right" src="' + window.baseURL + '{SRCIMGRIGHT}" alt="{NAME}"/>';
var zipTransform = '<a href="' + window.baseURL + '{SRCZIP}">{NAME}</a>';

var fileTransformOpt = {};

fileTransformOpt[imgTransform] = '[image={SRCIMG}]{NAME}[/image]';
fileTransformOpt[imgLeftTransform] = '[imageLeft={SRCIMGLEFT}]{NAME}[/imageLeft]';
fileTransformOpt[imgRightTransform] = '[imageRight={SRCIMGRIGHT}]{NAME}[/imageRight]';
fileTransformOpt[zipTransform] = '[zip={SRCZIP}]{NAME}[/zip]';

var wbbOpt = {
    hotkeys: false, //disable hotkeys (native browser combinations will work)
    showHotkeys: false, //hide combination in the tooltip when you hover.
    lang: "fr",
    traceTextarea: false,
    buttons: 'bold,italic,underline,strike,sup,sub,|,h2,h3,h4,h5,h6,|,warning,keynotion,|,img,zip,video,link,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,code,table,fullscreen',
    allButtons: {

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
        link: {
            title: 'Link',
            transform: {
                '<a class="external-link" href="{URL}">{SELTEXT}</a>': '[url={URL}]{SELTEXT}[/url]',
                '<a class="course-link" href="{ID}">{SELTEXT}</a>': '[course={ID}]{SELTEXT}[/course]'
            },
            cmd: addLink
        },
        code: {
            title: "Insert code snippet",
            buttonText: "code",
            cmd: codeModal,
            transform: {
                '<pre class={LANGUAGE}>{CODE}</pre>': '[code={LANGUAGE}]{CODE}[/code]'
            }
        },
        fullscreen: {
            title: 'Set Wysibb fullscreen',
            buttonText: 'fullscreen',
            cmd: fullScreen
        },
        keynotion: {
            title: 'Key notion',
            buttonText: 'key',
            transform: {
                '<div class="info info-keynotion"><i class="fa fa-key main"></i>{SELTEXT}</div>': '[keynotion]{SELTEXT}[/keynotion]'
            }
        },
        warning: {
            title: 'Warning',
            buttonText: 'warning',
            transform: {
                '<div class="info info-warning"><i class="fa fa-exclamation-triangle main"></i>{SELTEXT}</div>': '[warning]{SELTEXT}[/warning]'
            }
        }

    }
}
$(function () {
    $('#blogpost_add_description').wysibb(wbbOpt);
    $('#blogpost_add_content').wysibb(wbbOpt);
//	adjustToolbars();
//	$('.wysibb-toolbar').resize();
    $('.wysibb-text-editor').resize(adjustToolbars).trigger('resize');

});

