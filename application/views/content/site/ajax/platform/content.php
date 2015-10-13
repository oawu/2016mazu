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