/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.addPv = function (className, id) {
    $.ajax ({
        url: $('#ajax_pv_url').val (),
        data: { class: className, id: id },
        async: true, cache: false, dataType: 'json', type: 'POST',
    });
  };

  $('time').timeago ();
  $('._ic').imgLiquid ({verticalAlign: 'center'});
  $('._it').imgLiquid ({verticalAlign: 'top'});
});