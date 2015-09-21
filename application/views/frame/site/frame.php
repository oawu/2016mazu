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

    <nav>
      <div>
        <a href='<?php echo base_url ();?>' class='o'><div>北港</div><div><div>迎媽祖</div><div>Beigang Mazu</div></div></a>

        <div>
          <div class='l icon-menu'></div>
          <div>dsa</div>
        </div>

        <div>

          <div class='r icon-more'>
            <div class='c'></div>
            <div class='menu i<?php echo 1 + (User::current () ? array_intersect (Cfg::setting ('admin', 'roles'), User::current ()->roles ()) ? 2 : 1 : 1);?>'>
              <a class='icon-menu'>分享</a>
        <?php if (!User::current ()) { ?>
                <a class='login top_line icon-link-external' href='<?php echo Fb::loginUrl ('platform', 'fb_sign_in');?>'>登入</a>
        <?php } else {
                if (array_intersect (Cfg::setting ('admin', 'roles'), User::current ()->roles ())) { ?>
                  <a class='admin icon-menu top_line' href='<?php echo base_url ('admin');?>'>管理</a>
                  <a class='logout icon-menu' href='<?php echo Fb::logoutUrl ('platform', 'sign_out');?>'>登出</a>
          <?php } else { ?>
                  <a class='logout icon-menu top_line' href='<?php echo Fb::logoutUrl ('platform', 'sign_out');?>'>登出</a>
          <?php }
              }?>
            </div>
          </div>

        </div>
      </div>
    </nav>


    <div id='container'>
      <div>
        <div>
          <div>
            <div>北港<span>Mazu</span></div>
            <div><span>Beigang</span>迎媽祖</div>
          </div>

        <?php
          if ($menus) {
            foreach ($menus as $menu) { ?>
              <h4><?php echo $menu['text'];?></h4>
        <?php if ($menu['children']) { ?>
                <div>
            <?php foreach ($menu['children'] as $child) { ?>
                    <a href='<?php echo $child['href'];?>' class='<?php echo $child['icon'] . ($child['active'] ? ' active': '');?>' target='<?php echo $child['target'];?>'><?php echo $child['text'];?></a>
            <?php } ?>
                </div>
        <?php }
            }
          } ?>

        </div>
        <div>
          <?php echo isset ($content) ? $content : ''; ?>
        </div>
        <div></div>
      </div>
    </div>

    <div id='action' class='icon-plus'></div>

    <div id='loading'><svg class="svg" width="65px" height="65px" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg"><circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle></svg></div>

    <div id='footer'><div></div><div><div><?php echo Cfg::setting ('site', 'main', 'footer', 'title');?></div><div><?php echo Cfg::setting ('site', 'main', 'footer', 'description');?></div></div><div></div></div>

  </body>
</html>