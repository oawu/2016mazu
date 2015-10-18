/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $allVideos = $("iframe");

  $(window).resize(function() {
    $allVideos.height ($allVideos.width () * 9 / 16);
  }).resize();

  addPv ('Youtube', $('#id').val ());
  window.hideLoading ();
});