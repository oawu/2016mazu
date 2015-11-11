/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  function setTabLayout ($tab, $tabDivDiv, sum) {
    $tabDivDiv.css ({'left': 0});

    if ((sum ? sum : $tabDivDiv.width ()) > $tab.width ()) $tab.addClass ('o');
    else $tab.removeClass ('o');
  }

  var arrowWidth = 30;
  var $tab = $('._t').each (function () {
    var $that = $(this);
    var $tabDivDiv = $(this).find ('> div > div');
    var units = $.makeArray ($tabDivDiv.find ('> a').map (function () {
          return $(this).width () + parseFloat ($(this).css ('padding-left')) + parseFloat ($(this).css ('padding-right'));
        }));

    var sum = units.reduce (function (a, b) { return a + b; }) + 2;
    $tabDivDiv.width (sum);

    $(window).resize (function () {
      if ($that.get (0).timer) clearTimeout ($that.get (0).timer);
      $that.get (0).timer = setTimeout (setTabLayout.bind ($that, $that, $tabDivDiv, sum), 300);
    });
    setTabLayout ($that, $tabDivDiv, sum);

    var $arrow = $(this).find ('> a');
    $that.get (0).clickCount = $tabDivDiv.find ('> a.a').index () + 1;

    var $firstArrow = $arrow.first ().click (function () {
      if (units[$that.get (0).clickCount - 1]) $tabDivDiv.css ({'left': (--$that.get (0).clickCount < 1 ? 0 : (0 - units.slice (0, $that.get (0).clickCount).reduce (function (a, b) { return a + b; }))) + 'px'});
    });

    var $lastArrow = $arrow.last ().click (function () {
      if (units[$that.get (0).clickCount + 1]) $tabDivDiv.css ({'left': 0 - units.slice (0, $that.get (0).clickCount+++1).reduce (function (a, b) { return a + b; }) + 'px'});
    });

    $arrow.click (function () {
      if (units[$that.get (0).clickCount - 1]) $firstArrow.removeClass ('d');
      else $firstArrow.addClass ('d');
      if (units[$that.get (0).clickCount + 1]) $lastArrow.removeClass ('d');
      else $lastArrow.addClass ('d');
    });
    if ($that.hasClass ('o') && $that.get (0).clickCount--) $arrow.first ().click ();

    if ($that.get (0).tTimer) clearTimeout ($that.get (0).tTimer);
      $that.get (0).tTimer = setTimeout (function () {
        $that.addClass ('t');
      }, 500);
  });
});