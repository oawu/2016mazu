<div class='t'>
  <h2>
    <?php echo $dintao->title;?>
  </h2>
  <div class="fb-like" data-href="<?php echo base_url ('dintao', $dintao->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</div>

<article>
  <?php echo $dintao->content;?>
</article>

<?php
  if ($dintao->sources) { ?>
    <div class='s'>
      <h2>相關參考：</h2>
      <ul>
  <?php foreach ($dintao->sources as $source) {
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
        <a href='<?php echo base_url ('dintao', $prev->id . '-' . rawurlencode ($prev->title));?>'><?php echo $prev->title;?></a>
<?php }
      if ($next) { ?>
        <a href='<?php echo base_url ('dintao', $next->id . '-' . rawurlencode ($next->title));?>'><?php echo $next->title;?></a>
<?php } ?>
    </div>
<?php 
  }
