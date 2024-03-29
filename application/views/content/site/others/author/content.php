<figure>
  <a href='<?php echo current_url ();?>'>
    <img alt='<?php echo $other->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $other->cover->url ('1200x630c');?>' />
  </a>
  <figcaption><?php echo $other->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
</figure>

<div class='i'>
  <div>
    <figure>
      <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><img src='<?php echo $other->user->avatar ();?>' /></a>
    </figure>
    <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><?php echo $other->user->name ();?></a>
    <span>·</span>
    <time><?php echo $other->created_at->format ('Y.m.d');?></time>
  </div>
  <div class="fb-like" data-href="<?php echo base_url ('others', 'author');?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</div>

<article><?php echo preg_replace ('/<br\s*\/?>\n+/', '<br/>', $other->content);?></article>

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
  }