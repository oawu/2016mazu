/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $container = $('#container');
  var overflow = $('body').css ('overflow');

  $container.find ('> div > div').last ().click (function () {
    $container.removeClass ('show');
    $('body').css ('overflow', overflow);
  });
});