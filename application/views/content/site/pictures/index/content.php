<div class='as'>
<?php
  if ($pictures) {
    foreach ($pictures as $picture) { ?>
      <figure class='i_c' title='<?php echo $picture->title;?>' data-description='<?php echo $picture->mini_description (0);?>' href='<?php echo $picture->name->url ();?>' data-50x50c='<?php echo $picture->name->url ('50x50c');?>' data-fancybox-group='i'>
        <img src='<?php echo $picture->name->url ('300w');?>' alt='<?php echo $picture->title;?>' />
        <figcaption><?php echo $picture->title;?></figcaption>
        <a href='<?php echo base_url ('pictures', $picture->id . '-' . urlencode ($picture->title));?>' target='_blank'><?php echo $picture->title;?></a>
      </figure>
<?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
<?php
  }?>
</div>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
