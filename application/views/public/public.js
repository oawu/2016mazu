/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-46121102-23', 'auto');
ga('send', 'pageview');

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
Array.prototype.find = function (k, a) {
  return this[this.column (k).indexOf (a)];
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