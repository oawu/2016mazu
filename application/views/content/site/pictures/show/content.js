/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  addPv ('Picture', $('#id').val ());

  $('._c article figure a').fancybox ({
    padding: 0,
    helpers: {
      overlay: { locked: false },
      title: { type: 'over' },
      thumbs: { width: 50, height: 50 }
    }
  });
  $('._c figure a').imgLiquid ({verticalAlign: 'center'});

  window.hideLoading ();
});