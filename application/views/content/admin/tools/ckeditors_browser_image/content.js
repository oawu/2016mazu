/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
        // Helper function to get parameters from the query string.
function getUrlParam( paramName ) {
  var reParam = new RegExp ( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' );
  var match = window.location.search.match (reParam);
  return ( match && match.length > 1 ) ? match[1] : null;
}

$(function () {
  $('.ckes > div').click (function () {
    var funcNum = getUrlParam ('CKEditorFuncNum');
    var url = $(this).data ('url');
    window.opener.CKEDITOR.tools.callFunction (funcNum, url, function () {
      var dialog = this.getDialog ();
      if (dialog.getName () == 'image') {
        var element = dialog.getContentElement ('info', 'txtAlt');
        if (element) element.setValue ('alt text');
      }
      return url && url.length ? true : false;
    });
    window.close ();
  });
});