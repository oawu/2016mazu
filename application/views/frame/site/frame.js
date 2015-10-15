/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  window.addPv = function (className, pv) {
    $.ajax ({
        url: $('#ajax_pv_url').val (),
        data: {
          class: className,
          id: pv
        },
        async: true, cache: false, dataType: 'json', type: 'POST',
    });
  };
  $('.t_g').timeago ();
  $('.i_c').imgLiquid ({verticalAlign: 'center'});
  $('.i_t').imgLiquid ({verticalAlign: 'top'});
});