/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  addPv ('Dintao', $('#id').val ());

  $('._c figure a').imgLiquid ({verticalAlign: 'center'});

  $('._c article a').each (function () {
    $(this).attr ('target', '_blank');
  });

  $('._c article img').each (function () {
    
    var src = $(this).attr ('src').replace ('/400w_', '/_');

    $(this).attr ('data-fancybox-group', 'fancybox_group')
           .attr ('href', src);
  }).fancybox ({
    padding: 0,
    helpers: {
      overlay: { locked: false },
      title: { type: 'over' },
      thumbs: { width: 50, height: 50 }
    }
 });

  window.hideLoading ();
});