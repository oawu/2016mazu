<figure>
  <a href='<?php echo $dintao->content_page_url ($tag);?>'>
    <img alt='<?php echo $dintao->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $dintao->cover->url ('1200x630c');?>' />
  </a>
  <figcaption><?php echo $dintao->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
</figure>

<h2>
  <a href='<?php echo $dintao->content_page_url ($tag);?>'><?php echo $dintao->title;?></a>
  <div class="fb-like" data-href="<?php echo base_url ('dintao', $dintao->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</h2>

<div class='i'>
  <figure>
    <a href='<?php echo $dintao->user->facebook_link ();?>' target='_blank'><img src='<?php echo $dintao->user->avatar ();?>' /></a>
  </figure>
  <a href='<?php echo $dintao->user->facebook_link ();?>' target='_blank'>吳政賢</a>
  <span>·</span>
  <time><?php echo $dintao->created_at->format ('Y.m.d');?></time>
</div>

<article><?php echo str_replace ('alt=""', 'alt="' . str_replace ('"', '', $dintao->title) . ' - ' . Cfg::setting ('site', 'title') . '"', $dintao->content);?></article>

<?php
  if ($dintao->sources) { ?>
    <ul>
<?php foreach ($dintao->sources as $source) { ?>
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
  <div class='pv icon-eye2'><?php echo $dintao->pv;?> 人</div>
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