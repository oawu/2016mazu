<div class='ckes'>
<?php
  if ($ckes) {
    foreach ($ckes as $cke) { ?>
      <div class='cke _ic' data-url='<?php echo $cke->name->url ('400w');?>'><img src='<?php echo $cke->name->url ('400w');?>'></div>
<?php
    }
  } else { ?>
      <div>目前沒有任何圖片。</div>
  <?php
  } ?>
</div>