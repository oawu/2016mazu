<article>
<?php
  if ($pictures) {
    foreach ($pictures as $picture) { ?>
      <figure>
        <a href='<?php echo $picture->content_page_url ($tag);?>' data-id="<?php echo $picture->id;?>" data-size="<?php echo 2048;?>x<?php echo $picture->name_width ? (2048 / $picture->name_width) * $picture->name_height : 0;?>" src="<?php echo $picture->name->url ('2048w');?>" class='_ic'><img alt="<?php echo $picture->title;?>" src="<?php echo $picture->name->url ('500w');?>" /></a>
        <figcaption data-description='<?php echo $picture->mini_content (250);?>'><?php echo $picture->title;?></figcaption>
        <div class='icon-eye2'><?php echo $picture->pv;?></div>
      </figure>
<?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
<?php
  }?>
</article>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
