/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  // 使用 underscore 載入動態產生的 HTML template
  $('.attendees .add').click (function () {
    $(_.template ($('#_attendee').html (), {}) ({})).insertBefore ($(this).last ()).hide ().fadeIn();
  });

  // 定義動態產生的 HTML click event action
  $('body').on ('click', '.attendee .destroy', function () {
    $(this).parents ('div.attendee').fadeOut (function () {
      $(this).remove ();
    });
  });
});