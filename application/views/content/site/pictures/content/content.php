<div class='t'>
  <h2>
    <?php echo $picture->title;?>
  </h2>
  <div class="fb-like" data-href="<?php echo base_url ('picture', $picture->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</div>

<figure>
  <img alt="<?php echo $picture->title;?>" src="<?php echo $picture->name->url ();?>" />
  <figcaption><?php echo $picture->title;?></figcaption>
</figure>

<article>
  <?php echo $picture->description;?>
</article>

<?php
  if ($picture->sources) { ?>
    <div class='s'>
      <h2>相關參考：</h2>
      <ul>
  <?php foreach ($picture->sources as $source) {
          if ($source->href) { ?>
            <li><a href='<?php echo $source->href;?>' target='_blank'><?php echo $source->title ? $source->title : $source->href;?></a></li>
    <?php }
        } ?>
      </ul>
    </div>
<?php
  }

  if ($next && $prev) { ?>
    <div class='np'>
<?php if ($prev) { ?>
        <a href='<?php echo base_url ('picture', $method, $prev->id . '-' . rawurlencode ($prev->title));?>'><?php echo $prev->title;?></a>
<?php }
      if ($next) { ?>
        <a href='<?php echo base_url ('picture', $method, $next->id . '-' . rawurlencode ($next->title));?>'><?php echo $next->title;?></a>
<?php } ?>
    </div>
<?php 
  }
