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
      beforeSend: function () {
        window.showLoading();
      }
    })
    .done (function (result) {
      if (result.status) location.reload ();
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {
      window.hideLoading();
    });
  });

  $('a.destroy').click (function () {
    if (!confirm ('確定要刪除？'))
      return false;
    window.showLoading();
  });

  window.hideLoading();
});