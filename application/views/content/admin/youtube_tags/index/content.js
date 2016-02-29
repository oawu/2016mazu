/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.checkboxUpdateStatus (
    $('.index_checkbox input'),
    $('#is_on_site_url'),
    'is_on_site'
  );
  window.hideLoading ();
});