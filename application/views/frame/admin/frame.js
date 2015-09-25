/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.timeago').timeago ();
  $('.imgLiquid_center').imgLiquid ({verticalAlign: 'center'});
  autosize ($('textarea.autosize'));

  CKEDITOR.on ('dialogDefinition', function (ev) {

    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    if (dialogName == 'link') {
      var infoTab = dialogDefinition.getContents ('info');
      infoTab.remove ('linkType');
      dialogDefinition.getContents ('target').get ('linkTargetType')['default'] = '_blank';
    }
  });

  $('textarea.ckeditor').ckeditor ({
    filebrowserUploadUrl: $('#tools_ckeditors_upload_image_url').val (),
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
});