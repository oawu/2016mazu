<div>
  <div>
    <div>北港<span>Mazu</span></div>
    <div><span>Beigang</span>迎媽祖</div>
  </div>

<?php
  if ($menus_list = Cfg::setting ('menu', 'admin')) {
    foreach ($menus_list as $menus_text => $menus) { ?>
      <h4><?php echo $menus_text;?></h4>
<?php if ($menus) { ?>
        <div>
    <?php foreach ($menus as $menu_text => $menu) { ?>
            <a href='<?php echo $menu['href'];?>' class='<?php echo $menu['icon'] . (($menu['class'] && ($class == $menu['class']) && $menu['method'] && ($method == $menu['method'])) || ($menu['class'] && ($class == $menu['class']) && !$menu['method']) || (!$menu['class'] && $menu['method'] && ($method == $menu['method'])) ? ' active': '');?>' target='<?php echo $menu['target'];?>'><?php echo $menu_text;?></a>
    <?php } ?>
        </div>
<?php }
    }
  } ?>
</div>
