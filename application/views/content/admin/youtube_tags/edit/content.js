/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('td.k .icon-search').click (function () {
    var str = $('input[name="name"]').val ();
    if (str.length) {
      window.showLoading ();
      scws (str, function (w) {
        $(this).prev ().val (w.join (' '));
        window.hideLoading ();
      }.bind ($(this)));
    }
  });
  $('form').submit (function () {
    window.showLoading ();
  });
  window.hideLoading ();
});