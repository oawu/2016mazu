<?php
  if (!$tabs)
    return; ?>

<div class='_t'>
  <a class='icon-chevron-left'></a>
  <div>
    <div>
<?php foreach ($tabs as $text => $tab) { ?>
        <a href=''<?php echo ((isset ($tab['class']) && $tab['class']) && ($class == $tab['class']) && (isset ($tab['method']) && $tab['method']) && ($method == $tab['method'])) || (((isset ($tab['class']) && $tab['class'])) && ($class == $tab['class']) && !((isset ($tab['method']) && $tab['method']))) || (!(isset ($tab['class']) && $tab['class']) && (isset ($tab['method']) && $tab['method']) && ($method == $tab['method'])) ? " class='a'": '';?>><?php echo $text;?></a>
<?php } ?>
    </div>
  </div>
  <a class='icon-chevron-right'></a>
</div>