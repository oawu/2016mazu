<figure>
  <a>
    <img alt='<?php echo $store->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $store->cover->url ('1200x630c');?>' />
  </a>
  <figcaption><?php echo $store->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
</figure>

<h2>
  <a><?php echo $store->title;?></a>
  <div class="fb-like" data-href="<?php echo $store->content_page_url ();?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
</h2>

<div class='i'>
  <figure>
    <a href='<?php echo $store->user->facebook_link ();?>' target='_blank'><img src='<?php echo $store->user->avatar ();?>' /></a>
  </figure>
  <a href='<?php echo $store->user->facebook_link ();?>' target='_blank'>吳政賢</a>
  <span>·</span>
  <time><?php echo $store->created_at->format ('Y.m.d');?></time>
</div>

<article><?php echo str_replace ('alt=""', 'alt="' . str_replace ('"', '', $store->title) . ' - ' . Cfg::setting ('site', 'title') . '"', $store->content);?></article>

<?php
  if ($store->sources) { ?>
    <ul>
<?php foreach ($store->sources as $source) { ?>
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
<div class='pv icon-eye2'><?php echo $store->pv;?> 人</div>
