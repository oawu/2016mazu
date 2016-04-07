/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  autosize ($('textarea.autosize'));
  
  CKEDITOR.env.isCompatible = true;
  $('textarea.cke').ckeditor ({
    filebrowserUploadUrl: $('#tools_ckeditors_upload_image_url').val (),
    filebrowserImageBrowseUrl: $('#tools_ckeditors_browser_image_url').val (),
    height: 400,
  });

  window.scws = function (str, callback, beforeSend) {
    $.ajax ({
      url: $('#tools_scws_url').val (),
      data: { str: str },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: beforeSend ? beforeSend : function () {}
    })
    .done (function (result) {
      callback (result.status ? result.words : []);
    })
    .fail (function (result) { callback ([]); })
    .complete (function (result) {});
  };

  $('a.destroy, a[data-method="delete"]').click (function () {
    if (!confirm ('確定要刪除？'))
      return false;
    window.showLoading ();
  });



  var $tds = $('td.s');
  var $ma = $tds.find ('.ma');

  var $fm = function (i, t, h) {
    return $('<div />').addClass ('m').append (
      $('<div />').append ($('<a />').addClass ('icon-triangle-up').click (function () {
        var $p = $(this).parents ('.m');
        $p.clone (true).insertBefore ($p.index () > 0 ? $p.prev () : $ma);
        $p.remove ();
      })).append ($('<a />').addClass ('icon-triangle-down').click (function () {
        var $p = $(this).parents ('.m'), $x = $p.next (), $n = $p.clone (true);
        if ($x.hasClass ('ma')) $n.prependTo ($tds);
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

  if ($tds.data ('ms'))
    $tds.data ('ms').forEach (function (t) {
      $fm ($tds.data ('i'), t.title, t.href).insertBefore ($ma);
      $tds.data ('i', $tds.data ('i') + 1);
    });

  $ma.find ('.icon-plus').click (function () {
    $fm ($tds.data ('i')).insertBefore ($ma);
    $tds.data ('i', $tds.data ('i') + 1);
  }).click ();

  $('td.k .icon-search').click (function () {
    var str = $(this).data ('src').split (/\s*,\s*/).map (function (t) {
      return $(this).parents ('form').find (t).val ();
    }.bind ($(this))).join (' ');
    
    if (str.length) {
      window.showLoading ();
      scws (str, function (w) {
        $(this).prev ().val (w.join (' '));
        window.hideLoading ();
      }.bind ($(this)));
    }
  });

  $('textarea.cke').ckeditor ({
    height: 400,
    removeButtons: 'Subscript,Superscript,Save,NewPage,Print,Preview,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Form,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,PageBreak,Iframe,About,Styles,Image'
  });

  $('form').submit (function () {
    window.showLoading ();
  });
  
  window.checkboxUpdateStatus = function ($checkbox, $url, key) {
    var updateStatus = function (url, id, key, status, callback) {
      var data = {};
       data[key] = status ? 1 : 0;

      if ($url && $url.val ())
        $.ajax ({
          url: $url.val () + '/' + id,
          data: data,
          async: true, cache: false, dataType: 'json', type: 'post',
          beforeSend: function () { }
        })
        .done (callback ? callback : function (result) { })
        .fail (function (result) { ajaxError (result); })
        .complete (function (result) { });
    };

    $checkbox.change (function () {
      $(this).prop ('disabled', true)
             .nextAll ('div')
             .text ('設定中');

      updateStatus (
        $url,
        $(this).data ('id'),
        key,
        $(this).prop ('checked') === true,
        function (result) {
          $(this).prop ('disabled', false);
          if (result.content)
            $(this).nextAll ('div').text (result.content);
      }.bind ($(this)));

    });
  };
});