<div>
  <div>
    <div><div>北港</div><div>Mazu</div></div>
    <div><div>Beigang</div><div>迎媽祖</div></div>
  </div>

<?php
  if ($menus_list) {
    foreach ($menus_list as $menus_text => $menus) { ?>
<?php if ($menus = array_filter ($menus, function ($menu) { return !(isset ($menu['no_show']) && $menu['no_show']); })) { ?>
        <h4><?php echo $menus_text;?></h4>
        <div>
    <?php foreach ($menus as $menu_text => $menu) {
            if ($menu == 'line') { ?>
              <a class='l'></a>
      <?php } else { ?>
              <a href='<?php echo $menu['href'];?>'<?php echo ($icon = $menu['active'] || ((((isset ($menu['class']) && $menu['class']) && ($c == $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($m == $menu['method'])) || (((isset ($menu['class']) && $menu['class'])) && ($c == $menu['class']) && !((isset ($menu['method']) && $menu['method']))) || (!(isset ($menu['class']) && $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($m == $menu['method']))) && (!isset ($menu['uri']) || ($uri && ($menu['uri'] == $uri)))) ? $menu['icon'] ? $menu['icon'] . ' a' : 'a': $menu['icon']) ? " class='" . $icon . "'" : '';?><?php echo $menu['target'] == '_blank' ? 'target="_blank"' : '';?>><?php echo $menu_text;?></a>
      <?php }?>
    <?php } ?>
        </div>
<?php }
    }
  } ?>
</div>
