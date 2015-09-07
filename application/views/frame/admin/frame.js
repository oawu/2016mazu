/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
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