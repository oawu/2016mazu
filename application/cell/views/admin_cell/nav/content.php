<nav>
  <div>
    <a href='<?php echo base_url ();?>' class='o'><div>北港</div><div><div>迎媽祖</div><div>Beigang Mazu</div></div></a>

    <div>
      <div class='l icon-menu'></div>
      <div><?php echo isset ($subtitle) ? $subtitle : '';?></div>
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
              <a class='admin icon-menu top_line' href='<?php echo base_url ();?>'>前台</a>
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