var $ = window.jQuery || require('jquery');

$(window).scroll(function () {
  var scrollTop = $(this).scrollTop();
  $('.affix').each(function () {
    var $affix = $(this);
    if ($affix.hasClass('fixed')) {
      if ($affix.data('original-top') > scrollTop) {
        $affix.removeClass('fixed');
        $affix.css({
          left: initial,
          width: initial
        });
      }
    } else {
      var affixOffset = $affix.offset();
      if (affixOffset.top <= scrollTop && !$affix.hasClass('fixed')) {
        $affix.data('original-top', affixOffset.top);
        $affix.css({
          left: affixOffset.left,
          width: $affix.outerWidth()
        });
        $affix.addClass('fixed');
      }
    }
  });

});