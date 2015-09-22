/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $container = $('#container');
  var overflow = $('body').css ('overflow');
  
  $('nav .l').click (function () {
    if ($container.hasClass ('show')) {
      $container.removeClass ('show');
      $('body').css ('overflow', overflow);
    } else {
      $container.addClass ('show');
      $('body').css ('overflow', 'hidden');
    }
  });
  $('nav .r').click (function () {
    $(this).toggleClass ('show');
  }).find ('.admin, .login, .logout').click (function () {
    window.showLoading ();
  });
});