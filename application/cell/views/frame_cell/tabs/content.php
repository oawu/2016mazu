<?php
  if (!$tabs)
    return; ?>

<div class='_t'>
  <a class='icon-chevron-left'></a>
  <div>
    <div>
<?php foreach ($tabs as $text => $tab) { ?>
        <a href=''<?php echo ($index !== null) && isset ($tab['index']) && ($tab['index'] == $index) ? " class='a'": '';?>><?php echo $text;?></a>
<?php } ?>
    </div>
  </div>
  <a class='icon-chevron-right'></a>
</div>