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
      <?php } else { 
              $a = ((((isset ($menu['class']) && $menu['class']) && ($c == $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($m == $menu['method'])) || (((isset ($menu['class']) && $menu['class'])) && ($c == $menu['class']) && !((isset ($menu['method']) && $menu['method']))) || (!(isset ($menu['class']) && $menu['class']) && (isset ($menu['method']) && $menu['method']) && ($m == $menu['method']))) && (!isset ($menu['uri']) || ($uri && ($menu['uri'] == $uri))));
              $icon = $menu['active'] || $a ? $menu['icon'] ? $menu['icon'] . ' a' : 'a' : $menu['icon'];
              
              if ($a) { ?>
                <div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                  <a  itemprop="url" href='<?php echo $menu['href'];?>'<?php echo $icon ? " class='" . $icon . "'" : '';?><?php echo $a ? '' : '';?><?php echo $menu['target'] == '_blank' ? 'target="_blank"' : '';?>><div itemprop="title"><?php echo $menu_text;?></div></a>
                </div>
        <?php } else { ?>
                <a href='<?php echo $menu['href'];?>'<?php echo $icon ? " class='" . $icon . "'" : '';?><?php echo $a ? '' : '';?><?php echo $menu['target'] == '_blank' ? 'target="_blank"' : '';?>><?php echo $menu_text;?></a>
        <?php } ?>
      <?php }?>
    <?php } ?>
        </div>
<?php }
    }
  } ?>
</div>
