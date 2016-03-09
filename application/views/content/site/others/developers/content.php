<div class='i'>
  <figure>
    <a>
      <img alt='<?php echo $other->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $other->cover->url ('1200x630c');?>' />
    </a>
  </figure>

  <figure>
    <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><img src='<?php echo $other->user->avatar ();?>' /></a>
    <figcaption data-title='文章編輯'><?php echo $other->user->name;?></figcaption>
  </figure>

  <figure>
    <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><img src='<?php echo $other->user->avatar ();?>' /></a>
    <figcaption data-title='文章編輯'><?php echo $other->user->name;?></figcaption>
  </figure>

  <figure>
    <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><img src='<?php echo $other->user->avatar ();?>' /></a>
    <figcaption data-title='文章編輯'><?php echo $other->user->name;?></figcaption>
  </figure>

  <figure>
    <a href='<?php echo $other->user->facebook_link ();?>' target='_blank'><img src='<?php echo $other->user->avatar ();?>' /></a>
    <figcaption data-title='文章編輯'><?php echo $other->user->name;?></figcaption>
  </figure>
</div>

<article><?php echo preg_replace ('/<br\s*\/?>\n+/', '<br/>', $other->content);?></article>

<?php
  if (0) { ?>
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
  }?>