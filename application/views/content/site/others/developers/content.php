
<article><?php echo preg_replace ('/<br\s*\/?>\n+/', '<br/>', $other->content);?></article>

<?php
  if ($users) { ?>
    <div class='i'>
<?php foreach ($users as $user) { ?>
        <figure>
          <a href='<?php echo $user['href'];?>' target='_blank'><img src='<?php echo $user['src'];?>' /></a>
          <figcaption data-title='<?php echo $user['title'];?>'><?php echo $user['name'];?></figcaption>
        </figure>
<?php } ?>
    </div>
<?php
  }
?>

<?php
  if ($other->sources) { ?>
    <ul>
<?php foreach ($other->sources as $source) { ?>
        <li>
    <?php if ($source->title) { ?>
            <a href='<?php echo $source->href;?>' target='_blank'><?php echo $source->title;?></a><span><a href='<?php echo $source->href;?>' target='_blank'><?php echo $source->mini_href (40);?></a></span>
    <?php } else { ?>
            <a href='<?php echo $source->href;?>' target='_blank'><?php echo $source->mini_href ();?></a>
    <?php } ?>
        </li>
<?php } ?>
    </ul>
  <?php
  } ?>
  <div class='pv icon-eye2'><?php echo $other->pv;?> 人</div>

<?php
  if ($prev || $next) { ?>
    <div class='np'>
<?php if ($prev) { ?>
        <figure class='p'>
          <a href='<?php echo $prev->content_page_url ();?>'>
            <img src='<?php echo $prev->cover->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $prev->content_page_url ();?>'><?php echo $prev->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }
      if ($next) {?>
        <figure class='n'>
          <a href='<?php echo $next->content_page_url ();?>'>
            <img src='<?php echo $next->cover->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $next->content_page_url ();?>'><?php echo $next->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }?>
    </div>
<?php 
  } ?>
