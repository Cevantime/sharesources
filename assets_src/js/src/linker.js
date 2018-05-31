var clipboard = require('clipboard-polyfill');

$(function () {
  $('.article > *, .tutorial > *').each(function () {
    var $el = $(this);
    if ($el.attr('id')) {
      var offset = $el.offset();
      offset.top = offset.top + $el.height() / 2 - 10;
      offset.left = offset.left - 20;
      var $a = $('<a>').addClass('linker').attr('href', "#").text('#').click(function (e) {
        e.preventDefault();
        var anchorPos = window.location.href.indexOf('#');
        var url;
        if( anchorPos === -1){
          url = window.location.href;
        } else {
          url = window.location.href.substring(0, anchorPos);
        }
        url += "#" + $el.attr('id');
        var dt = new clipboard.DT();
        dt.setData('text/plain', url);
        clipboard.write(dt);
      }).prependTo($el);
      $el.hover(function () {
        $('.linker').removeClass('displayed');
        $a.addClass('displayed');

      });
    }
  });
});