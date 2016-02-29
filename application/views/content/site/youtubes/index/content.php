<article>
  <?php
  if ($youtubes) {
    foreach ($youtubes as $youtube) { ?>
      <figure>
        <a href='<?php echo $youtube->content_page_url ($tag);?>'><img alt='<?php echo $youtube->title;?>' src='<?php echo $youtube->cover->url ('500w');?>' /></a>
        <figcaption><a href='<?php echo $youtube->content_page_url ($tag);?>'><?php echo $youtube->title;?></a></figcaption>
        <span class='icon-eye2'><?php echo $youtube->pv;?></span>
      </figure>
  <?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
  <?php
  } ?>
</article>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
