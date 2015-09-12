/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.sort a').click (function () {
    $.ajax ({
      url: $('#sort').val (),
      data: {
        id: $(this).data ('id'),
        sort: $(this).data ('sort')
      },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () { }
    })
    .done (function (result) {
      if (result.status) location.reload ();
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  });

  $('.icon-bin').click (function () {
    if (!window.confirm ("刪除後子選項也會一併刪除，確定刪除？"))
      return false;
  });
  window.hideLoading ();
});