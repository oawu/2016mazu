/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.checkboxUpdateStatus (
    $('.index_checkbox input'),
    $('#is_enabled_url'),
    'is_enabled'
  );
  window.hideLoading ();
});