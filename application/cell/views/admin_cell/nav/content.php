<nav>
  <div class='container'>
    <a href='<?php echo base_url ();?>' class='logo'><div>北港</div><div><div>迎媽祖</div><div>Beigang Mazu</div></div></a>

    <div class='left'><div class='option icon-menu'></div><div class='title'></div></div>

    <div class='right'>

      <div class='option icon-more white'>
        <div class='cover'></div>
        <div class='menu i<?php echo 1 + (User::current () ? in_array ('admin', User::current ()->role_names ()) ? 2 : 1 : 1);?>'>
          <a class='icon-menu'>分享</a>
    <?php if (!User::current ()) { ?>
            <a href='<?php echo Fb::loginUrl ('platform', 'fb_sign_in');?>' class='top_line icon-link-external'>登入</a>
    <?php } else {
            if (in_array ('admin', User::current ()->role_names ())) { ?>
              <a class='icon-menu top_line' href='<?php echo base_url ('admin');?>'>管理</a>
              <a class='icon-menu' href='<?php echo Fb::logoutUrl ('platform', 'sign_out');?>'>登出</a>
      <?php } else { ?>
              <a class='icon-menu top_line' href='<?php echo Fb::logoutUrl ('platform', 'sign_out');?>'>登出</a>
      <?php }
          }?>
        </div>
      </div>

    </div>
  </div>
</nav>