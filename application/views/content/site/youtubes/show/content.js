/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  addPv ('Youtube', $('#id').val ());

  $('._c article figure a').fancybox ({
    padding: 0,
    helpers: {
      overlay: { locked: false },
      title: { type: 'over' },
      thumbs: { width: 50, height: 50 }
    }
  });
  $('._c figure a').imgLiquid ({verticalAlign: 'center'});

  var $allVideos = $("iframe");

  $(window).resize(function() {
    $allVideos.height ($allVideos.width () * 9 / 16);
  }).resize();

  window.hideLoading ();
});