/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  function update (id, val, column, $obj) {
    $.ajax ({
      url: $('#update_url').val (),
      data: { id: id, val: val, column: column },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {
        $obj.addClass ('l');
      }
    })
    .done (function (result) {
      if (result.status) $obj.addClass ('ok');
      else $obj.addClass ('uok');
    })
    .fail (function (result) {})
    .complete (function (result) {
      // clearTimeout ($obj.get (0).timer);
      // $obj.get (0).timer = setTimeout (function () {
      //   $obj.removeClass ('l ok uok');
      // }, 1500);
    });
  }
  $('.t').dblclick (function () {
    var val = $(this).text ();
    
    $(this).removeClass ('l ok uok').empty ().append ($('<input />').attr ('type', 'text').val (val).focusout (function () {
      var val = $(this).find ('input').val ();
      $(this).empty ().text (val);
      update ($(this).data ('id'), val, 'title', $(this));
    }.bind ($(this)))).find ('input').focus ();
  });
  $('.c').dblclick (function () {
    var val = $(this).text ();
    
    $(this).removeClass ('l ok uok').empty ().append ($('<textarea />').val (val).focusout (function () {
      var val = $(this).find ('textarea').val ();
      $(this).empty ().text (val);
      update ($(this).data ('id'), val, 'content', $(this));
    }.bind ($(this))));
  });
});