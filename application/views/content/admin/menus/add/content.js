/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $class = $('select[name="class"]');
  var $method = $('select[name="method"]');

  $class.change (function () {
    $method.empty ().append ($('<option />').val ('').text ('請選擇方法')).append ($(this).find ('option:selected').data ('methods').map (function (t) {
      return $('<option />').val (t).text (t);
    }));
  }).change ();

  $method.find ('option').each (function () {
    if ($(this).val () == $method.data ('method'))
      $(this).prop ('selected', true);
  });

  window.hideLoading ();
});