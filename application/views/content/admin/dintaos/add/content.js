/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $td = $('td.s');
  var $ma = $td.find ('.ma');

  var $m = function (i, t, h) {
    return $('<div />').addClass ('m').append (
      $('<div />').append ($('<a />').addClass ('icon-triangle-up2').click (function () {
        var $p = $(this).parents ('.m');
        $p.clone (true).insertBefore ($p.index () > 0 ? $p.prev () : $ma);
        $p.remove ();
      })).append ($('<a />').addClass ('icon-triangle-down2').click (function () {
        var $p = $(this).parents ('.m'), $x = $p.next (), $n = $p.clone (true);
        if ($x.hasClass ('ma')) $n.prependTo ($td);
        else $n.insertAfter ($x);
        $p.remove ();
      }))
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][title]').attr ('placeholder', '請輸入參考來源名稱..').attr ('maxlength', 200).val (t ? t : '')
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][href]').attr ('placeholder', '請輸入參考來源網址..').val (h ? h : '')
    ).append (
      $('<button />').attr ('type', 'button').addClass ('icon-bin').click (function () {
        $(this).parents ('.m').remove ();
      })
    );
  };

  var i = 0;
  if ($('td.s').data ('ms'))
    $('td.s').data ('ms').forEach (function (t, j) {
      $m (i = j, t.title, t.href).insertBefore ($ma);
    });
  $ma.find ('.icon-plus').click (function () {
    $m (i = i ? ++i : $ma.index ()).insertBefore ($ma);
  }).click ();

  $('form').submit (function () {
    window.showLoading();
  });
  window.hideLoading();
});