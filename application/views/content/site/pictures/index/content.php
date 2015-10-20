<div class='as'>
<?php
  if ($pictures) {
    foreach ($pictures as $picture) { ?>
      <figure>
        <a href='<?php echo base_url ('picture', $method, $picture->site_content_page_last_uri ());?>' data-size="<?php echo $picture->width;?>x<?php echo $picture->height;?>" src="<?php echo $picture->name->url ();?>" class='i_c'>
          <img alt="<?php echo $picture->title;?>" src="<?php echo $picture->name->url ('300w');?>" />
        </a>
        <figcaption data-description='<?php echo $picture->mini_description (250);?>'><?php echo $picture->title;?></figcaption>
      </figure>
<?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
<?php
  }?>
</div>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
