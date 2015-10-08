/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('figure.i_c').fancybox ({
    openEffect: 'elastic',
    closeEffect: 'elastic',
    padding: 0,
    helpers: {
      overlay: { locked: false },
      title: {
        type: 'over',
        position: 'top',
      },
      thumbs: {
        width: 50,
        height: 50,
        source: function (i) {
          return $(i.element).data ('50x50c');
        }
      }
    },
    beforeShow: function(){
      var $that = $(this.element);
      var $a = $that.find ('a');
      if ($a.length)
        this.title = $a.empty ().append ($('<span />').text (this.title)).append ($('<span />').addClass ('icon-link-external')).prop ('outerHTML');
      else
        this.title = '';

      if ($that.data ('description'))
        this.title += '<div><div>' + $('<div />').html ($that.data ('description')).text () + '</div></div>';
    }
  });
  window.hideLoading ();
});