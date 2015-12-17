/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  window.hideLoading ();
  window.body = $('body');


  window.onhashchange = function() {
    var hash = window.location.hash.trim ().slice (1);
    if (hash.length) window.body.stop ().animate ({ scrollTop: $('article[data-tag="' + hash + '"]').offset ().top - 120 }, 300);
  };
  $('._t > div > div > a').click (function () {
    $(this).addClass ('a').siblings ().removeClass ('a');
  }).filter ('[href^="' + window.location.href + '"]').click ();
});