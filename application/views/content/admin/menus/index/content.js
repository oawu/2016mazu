/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.icon-bin').click (function () {
    if (!window.confirm ("刪除後子選項也會一併刪除，確定刪除？"))
      return false;
  });
  window.hideLoading ();
});