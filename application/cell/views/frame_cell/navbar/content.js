/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $container = $('#container');
  var overflow = $('body').css ('overflow');
  var navTimer = null;
  
  $('nav .l').click (function () {
    if ($container.hasClass ('show')) {
      $container.removeClass ('show');
      $('body').css ('overflow', overflow);
    } else {
      $container.addClass ('show');
      $('body').css ('overflow', 'hidden');
    }
  });

  $('nav .r').click (function () {
    $(this).toggleClass ('show');

    clearTimeout (navTimer);
    
    navTimer = setTimeout (function () {
      $.ajax ({
          url: $('#ajax_navbar_url').val (),
          data: {
            type: $(this).data ('type')
          },
          async: true, cache: false, dataType: 'json', type: 'GET',
      })
      .done (function (result) {
        if (!result.status) $(this).remove ();
        $(this).parent ().empty ().html (result.content).find ('.r').addClass ('show').click (function () {
          $(this).toggleClass ('show');
        });
      }.bind ($(this)))
      .fail (function (result) {
        $(this).remove ();
      }.bind ($(this)))
      .complete (function (result) {});
    }.bind ($(this)), 300);
  }).find ('.admin, .login, .logout').click (function () {
    window.showLoading ();
  });

  $('nav>div>div:nth-child(3)').on ('click', '>a.share', function () {
    window.open ('https://www.facebook.com/sharer/sharer.php?u=' + window.location.href, '分享', 'scrollbars=yes,resizable=yes,toolbar=no,location=yes,width=550,height=420,top=100,left=' + (window.screen ? Math.round(screen.width / 2 - 275) : 100));
    return false;
  });
});