<!DOCTYPE html>
<html lang="zh">
  <head>
<?php echo isset ($meta_list) ? $meta_list : ''; ?>
    <title><?php echo isset ($title) ? $title : ''; ?></title>
<?php echo isset ($css_list) ? $css_list : ''; ?>
<?php echo isset ($js_list) ? $js_list : ''; ?>
  </head>
  <body lang="zh-tw">
<?php echo isset ($hidden_list) ? $hidden_list : ''; ?>

<?php echo render_cell ('admin_frame_cell', 'header', isset ($subtitle) ? $subtitle : '', isset ($back_link) ? $back_link : '');?>

    <div id='container'>
      <div>
        <?php echo render_cell ('admin_frame_cell', 'wrapper_left', $_menus_list, isset ($class) && $class ? $class : null, isset ($method) && $method ? $method : null, isset ($uri) && $uri ? $uri : null);?>
        <div>
          <?php echo render_cell ('admin_frame_cell', 'tabs', isset ($tabs) ? $tabs : array (), isset ($tab_index) ? $tab_index : null);?>

          <div class='_c'>
            <?php echo isset ($content) ? $content : ''; ?>
          </div>

    <?php if ($_flash_message = Session::getData ('_flash_message', true)) { ?>
            <div class='_m'><?php echo $_flash_message;?></div>
    <?php }?>
        </div>
        <div></div>
      </div>
    </div>

    <?php echo render_cell ('admin_frame_cell', 'footer');?>

    <div id='loading' class='hide'><svg class="svg" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>
    <div id='fb-root'></div>

<?php if (isset ($has_photoswipe) && $has_photoswipe) { ?>
        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true"><div class="pswp__bg"></div><div class="pswp__scroll-wrap"><div class="pswp__container"><div class="pswp__item"></div><div class="pswp__item"></div><div class="pswp__item"></div></div><div class="pswp__ui pswp__ui--hidden"><div class="pswp__top-bar"><div class="pswp__counter"></div><button class="pswp__button pswp__button--close" title="關閉 (Esc)"></button><button class="pswp__button pswp__button--share" title="分享"></button><button class="pswp__button pswp__button--link" title="鏈結"></button><button class="pswp__button pswp__button--fs" title="全螢幕切換"></button><button class="pswp__button pswp__button--zoom" title="放大/縮小"></button><div class="pswp__preloader"><div class="pswp__preloader__icn"><div class="pswp__preloader__cut"><div class="pswp__preloader__donut"></div></div></div></div></div><div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap"><div class="pswp__share-tooltip"></div> </div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button><button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button><div class="pswp__caption"><div class="pswp__caption__center"></div></div></div></div></div>
<?php }?>
  </body>
</html>