<nav>
  <div>
    <a href='<?php echo base_url ();?>' class='o'><div>北港</div><div><div>迎媽祖</div><div>Beigang Mazu</div></div></a>

    <div>
<?php if ($back_link) { ?>
        <a class='icon-arrow-left' href='<?php echo $back_link;?>'></a>
<?php } else { ?>
        <div class='l icon-menu'></div>
<?php }?>
      <h1><?php echo $subtitle;?></h1>
    </div>

    <div>
      <div class='r icon-more'>
        <div class='c'></div>
        <div class='menu i<?php echo count ($menus);?>'>
      <?php foreach ($menus as $menu) { ?>
              <a<?php echo $menu['class'] ? " class='" . $menu['class'] . "'": '';?><?php echo $menu['href'] ? " href='" . $menu['href'] . "'": '';?>><?php echo $menu['text'];?></a>
      <?php } ?>
        </div>
      </div>
<?php if ($type == 'site') { ?>
        <div class='icon-share2 b'></div>
<?php } ?>
    </div>
  </div>
</nav>