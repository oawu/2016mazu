/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $container = $('#container');
  var $leftOption = $('nav .left .option');
  var $rightOption = $('nav .right .option');
  var $wrapperCover = $container.find ('.cover');
  var overflow = $('body').css ('overflow');

  $leftOption.click (function () {
    if ($container.hasClass ('show')) {
      $container.removeClass ('show');
      $('body').css ('overflow', overflow);
    } else {
      $container.addClass ('show');
      $('body').css ('overflow', 'hidden');
    }
  });
  $wrapperCover.click (function () {
    $container.removeClass ('show');
    $('body').css ('overflow', overflow);
  });
  $rightOption.click (function () {
    $(this).toggleClass ('show');
  });
});