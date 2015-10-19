<div class='as'>
<?php
  if ($youtubes) {
    foreach ($youtubes as $youtube) { ?>
      <a href='<?php echo base_url ('youtube', $method, $youtube->id . '-' . rawurlencode ($youtube->title));?>'>
        <figure>
          <img alt="<?php echo $youtube->title;?>" src="<?php echo $youtube->cover->url ('300w');?>" />
          <figcaption><?php echo $youtube->title;?></figcaption>
        </figure>
      </a>
<?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
<?php
  }?>
</div>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>