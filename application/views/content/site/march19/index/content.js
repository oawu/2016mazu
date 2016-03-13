/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $like = $('.fb-like');
  var $h2 = $like.next ().find ('>section>h2').first ();
  console.error ();
  
  $(window).resize (function () {
  //   $like.css ('top': );
  console.error ($h2.offset ());
  
  }).resize ()
  window.hideLoading ();
});