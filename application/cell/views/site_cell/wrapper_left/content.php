<div class='logo'>
  <div>北港<span>Mazu</span></div>
  <div><span>Beigang</span>迎媽祖</div>
</div>
<?php
  if ($item_lists) {
    foreach ($item_lists as $title => $item_list) { ?>
      <div class='title'><?php echo $title;?></div>
<?php if ($item_list) { ?>
        <div class='group'>
    <?php foreach ($item_list as $item) {
            if ($item['visible']) {?>
              <a href='<?php echo $item['href'];?>' class='<?php echo $item['icon'] . ($item['active'] ? ' active': '');?>' target='<?php echo $item['target'];?>'><?php echo $item['name'];?></a>
      <?php }
          } ?>
        </div>
<?php }
    }
  }
