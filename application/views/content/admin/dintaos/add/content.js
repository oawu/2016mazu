/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $m = function (i) {
    return $('<div />').addClass ('m').append (
      $('<div />').append ($('<a />').addClass ('icon-triangle-up2').click (function () {
        var $p = $(this).parents ('.m');
        $p.clone (true).insertBefore ($p.index () > 0 ? $p.prev () : $(this).parents ('td').find ('.ma'));
        $p.remove ();
      })).append ($('<a />').addClass ('icon-triangle-down2').click (function () {
        var $p = $(this).parents ('.m'), $x = $p.next (), $n = $p.clone (true);
        if ($x.hasClass ('ma')) $n.prependTo ($(this).parents ('td'));
        else $n.insertAfter ($x);
        $p.remove ();
      }))
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][title]').attr ('placeholder', '請輸入參考來源名稱..').attr ('maxlength', 200)
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][href]').attr ('placeholder', '請輸入參考來源網址..')
    ).append (
      $('<button />').attr ('type', 'button').addClass ('icon-bin').click (function () {
        $(this).parents ('.m').remove ();
      })
    );
  };

  var i = 0;
  $('.ma .icon-plus').click (function () {
    var $ma = $(this).parents ('.ma');
    $m (i = i ? ++i : $ma.index ()).insertBefore ($ma);
  }).click ();

  window.hideLoading();
});