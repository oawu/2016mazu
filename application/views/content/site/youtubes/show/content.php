<div class='y'>
  <iframe src="//www.youtube.com/embed/<?php echo $youtube->vid;?>?autoplay=1" frameborder="0" allowfullscreen=""></iframe>
</div>

<h2>
  <a href='<?php echo $youtube->content_page_url ($tag);?>'><?php echo $youtube->title;?></a>
  <div class="fb-like" data-href="<?php echo base_url ('youtube', $youtube->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</h2>

<div class='i'>
  <figure>
    <a href='<?php echo $youtube->user->facebook_link ();?>' target='_blank'><img src='<?php echo $youtube->user->avatar ();?>' /></a>
  </figure>
  <a href='<?php echo $youtube->user->facebook_link ();?>' target='_blank'>吳政賢</a>
  <span>·</span>
  <time><?php echo $youtube->created_at->format ('Y.m.d');?></time>
</div>

<article>
  <?php echo $youtube->content;?>
</article>

<?php
  if ($youtube->sources) { ?>
    <ul>
<?php foreach ($youtube->sources as $source) { ?>
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
  }  ?>
  <div class='pv icon-eye2'><?php echo $youtube->pv;?> 人</div>
<?php
  if ($prev || $next) { ?>
    <div class='np'>
<?php if ($prev) { ?>
        <figure class='p'>
          <a href='<?php echo $prev->content_page_url ($tag);?>'>
            <img src='<?php echo $prev->cover->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $prev->content_page_url ($tag);?>'><?php echo $prev->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }
      if ($next) {?>
        <figure class='n'>
          <a href='<?php echo $next->content_page_url ($tag);?>'>
            <img src='<?php echo $next->cover->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $next->content_page_url ($tag);?>'><?php echo $next->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }?>
    </div>
<?php 
  }?>