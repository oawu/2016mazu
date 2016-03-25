/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
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
Array.prototype.last = function () {
  return this[this.length - 1];
};
Array.prototype.first = function () {
  return this[0];
};

window.ajaxError = function (result) {
  console.error (result.responseText);
};

window.fbAsyncInit = function() {
  FB.init({
    appId      : '695906407210191',
    xfbml      : true,
    version    : 'v2.4'
  });
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/zh_TW/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

$(function () {
  window.mainLoading = $('#loading');
  window.showLoading = function (callback) {
    this.mainLoading.fadeIn (function () { $(this).removeClass ('hide'); if (callback) callback (); });
  };

  window.hideLoading = function (callback) {
    clearTimeout (window.showLoadingTimer); this.mainLoading.addClass ('hide').fadeOut (function () { $(this).hide (function () { if (callback) callback (); }); });
  };

  window.closeLoading = function (callback) {
    window.hideLoading (function  () { if (callback) callback (); window.mainLoading.remove (); });
  };

  window.showLoadingTimer = setTimeout (function () { window.showLoading (); }, 100);
  
  $('time').timeago ();
  $('._ic').imgLiquid ({verticalAlign: 'center'});
  $('._it').imgLiquid ({verticalAlign: 'top'});
});