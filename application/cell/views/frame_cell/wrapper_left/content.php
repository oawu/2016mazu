<div>
  <div>
    <div>北港<span>Mazu</span></div>
    <div><span>Beigang</span>迎媽祖</div>
  </div>

<?php
  if ($menus_list) {
    foreach ($menus_list as $menus_text => $menus) { ?>
      <h4><?php echo $menus_text;?></h4>
<?php if ($menus) { ?>
        <div>
    <?php foreach ($menus as $menu_text => $menu) { ?>
            <a href='<?php echo $menu['href'];?>'<?php echo ($c = ((isset ($menu['class']) && $menu['class']) && ($class == $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($method == $menu['method'])) || (((isset ($menu['class']) && $menu['class'])) && ($class == $menu['class']) && !((isset ($menu['method']) && $menu['method']))) || (!(isset ($menu['class']) && $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($method == $menu['method'])) ? $menu['icon'] ? $menu['icon'] . ' a' : 'a': $menu['icon']) ? " class='" . $c . "'" : '';?><?php echo $menu['target'] == '_blank' ? 'target="_blank"' : '';?>><?php echo $menu_text;?></a>
    <?php } ?>
        </div>
<?php }
    }
  } ?>
</div>
