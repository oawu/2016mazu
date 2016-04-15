<figure>
  <img alt='<?php echo $picture->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $picture->name->url ('2048w');?>' />
  <figcaption><?php echo $picture->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
  <a href='<?php echo $picture->name->url ('2048w');?>' class='icon-zoomin'></a>
</figure>

<h2>
  <a href='<?php echo $picture->content_page_url ($tag);?>'><?php echo $picture->title;?></a>
  <div class="fb-like" data-href="<?php echo base_url ('picture', $picture->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</h2>

<div class='i'>
  <figure>
    <a href='<?php echo $picture->user->facebook_link ();?>' target='_blank'><img src='<?php echo $picture->user->avatar ();?>' /></a>
  </figure>
  <a href='<?php echo $picture->user->facebook_link ();?>' target='_blank'><?php echo $picture->user->name ();?></a>
  <span>·</span>
  <time><?php echo $picture->created_at->format ('Y.m.d');?></time>
</div>

<article><?php echo str_replace ('alt=""', 'alt="' . str_replace ('"', '', $picture->title) . ' - ' . Cfg::setting ('site', 'title') . '"', $picture->content);?></article>

<?php
  if ($picture->sources) { ?>
    <ul>
<?php foreach ($picture->sources as $source) { ?>
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
  <div class='pv icon-eye2'><?php echo $picture->pv;?> 人</div>
<?php
  if ($prev || $next) { ?>
    <div class='np'>
<?php if ($prev) { ?>
        <figure class='p'>
          <a href='<?php echo $prev->content_page_url ($tag);?>'>
            <img src='<?php echo $prev->name->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $prev->content_page_url ($tag);?>'><?php echo $prev->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }
      if ($next) {?>
        <figure class='n'>
          <a href='<?php echo $next->content_page_url ($tag);?>'>
            <img src='<?php echo $next->name->url ('100x100c');?>' />
          </a>
          <figcaption><a href='<?php echo $next->content_page_url ($tag);?>'><?php echo $next->mini_title ();?></a></figcaption>
        </figure>
      <?php
      }?>
    </div>
<?php 
  }?>