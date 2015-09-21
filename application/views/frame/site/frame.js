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

  $container.find ('> div > div').last ().click (function () {
    $container.removeClass ('show');
    $('body').css ('overflow', overflow);
  });

  $('.timeago').timeago ();
  $('.imgLiquid_center').imgLiquid ({verticalAlign: 'center'});

  window.mainLoading = $('#loading');
  window.showLoading = function (callback) {
    this.mainLoading.fadeIn (function () {
      $(this).removeClass ('hide');
      if (callback)
        callback ();
    });
  };

  window.hideLoading = function (callback) {
    this.mainLoading.addClass ('hide').fadeOut (function () {
      $(this).hide (function () {
        if (callback)
          callback ();
      });
    });
  };

  window.closeLoading = function (callback) {
    window.hideLoading (function  () {
      if (callback)
        callback ();
        window.mainLoading.remove ();
    });
  };
});