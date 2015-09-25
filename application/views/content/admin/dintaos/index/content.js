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

  window.hideLoading();
});