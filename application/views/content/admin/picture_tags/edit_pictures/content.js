/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('td.k .icon-search').click (function () {
    var str = $('input[name="title"]').val () + $('input[name="description"]').val ();
    if (str.length) {
      window.showLoading ();
      scws (str, function (w) {
        $(this).prev ().val (w.join (' '));
        window.hideLoading ();
      }.bind ($(this)));
    }
  });
  $('form').submit (function () {
    window.showLoading ();
  });
  $('textarea.cke').ckeditor ({
    height: 400,
    removeButtons: 'Subscript,Superscript,Save,NewPage,Print,Preview,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Form,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,PageBreak,Iframe,About,Styles,Image'
  });
  window.hideLoading ();
});