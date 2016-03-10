<section>
  <?php
  if ($articles) {
    foreach ($articles as $article) {
      $href = $article->content_page_url ($tag); ?>
      <article>
        <h2>
          <a href='<?php echo $href;?>'><?php echo $article->title;?></a>
          <time datetime='<?php echo $article->created_at->format ('Y-m-d H:i:s');?>'><?php echo $article->created_at->format ('Y-m-d H:i:s');?></time>
        </h2>

        <figure>
          <a href='<?php echo $href;?>'>
            <img alt='<?php echo $article->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $article->cover->url ('500w');?>' />
          </a>
          <figcaption><?php echo $article->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
        </figure>

        <div>
          <div><?php echo $article->mini_content (350);?></div>
          <div>
            <div><?php echo implode (' . ', $article->keywords ());?></div>
            <div><a href='<?php echo $href;?>'>詳細內容</a></div>
          </div>
        </div>
      </article>
  <?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
  <?php
  } ?>
</section>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
