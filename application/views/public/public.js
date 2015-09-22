/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

Array.prototype.column = function (k) {
  return this.map (function (t) { return k ? eval ("t." + k) : t; });
};
Array.prototype.diff = function (a, k) {
  return this.filter (function (i) { return a.column (k).indexOf (eval ("i." + k)) < 0; });
};
Array.prototype.max = function (k) {
  return Math.max.apply (null, this.column (k));
};
Array.prototype.min = function (k) {
  return Math.min.apply (null, this.column (k));
};

window.ajaxError = function (result) {
  console.error (result.responseText);
};

$(function () {
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