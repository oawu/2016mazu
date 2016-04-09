<figure itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <a itemprop="url" href='<?php echo $article->content_page_url ($tag);?>'>
    <img alt='<?php echo $article->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $article->cover->url ('1200x630c');?>' />
  </a>
  <figcaption><?php echo $article->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
  <span itemprop="title"><?php echo $article->title;?></span>
</figure>

<h2>
  <a href='<?php echo $article->content_page_url ($tag);?>'><?php echo $article->title;?></a>
  <div class="fb-like" data-href="<?php echo base_url ('article', $article->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</h2>

<div class='i'>
  <figure>
    <a href='<?php echo $article->user->facebook_link ();?>' target='_blank'><img src='<?php echo $article->user->avatar ();?>' /></a>
  </figure>
  <a href='<?php echo $article->user->facebook_link ();?>' target='_blank'>吳政賢</a>
  <span>·</span>
  <time><?php echo $article->created_at->format ('Y.m.d');?></time>
</div>

<article><?php echo str_replace ('alt=""', 'alt="' . str_replace ('"', '', $article->title) . ' - ' . Cfg::setting ('site', 'title') . '"', $article->content);?></article>

<?php
  if ($article->sources) { ?>
    <ul>
<?php foreach ($article->sources as $source) { ?>
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
  <div class='pv icon-eye2'><?php echo $article->pv;?> 人</div>
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