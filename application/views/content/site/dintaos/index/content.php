<section>
  <?php
  if ($dintaos) {
    foreach ($dintaos as $dintao) {
      $href = $dintao->content_page_url ($tag); ?>
      <article>
        <h2>
          <a href='<?php echo $href;?>'><?php echo $dintao->title;?></a>
          <time datetime='<?php echo $dintao->created_at->format ('Y-m-d H:i:s');?>'><?php echo $dintao->created_at->format ('Y-m-d H:i:s');?></time>
        </h2>

        <figure>
          <a href='<?php echo $href;?>'>
            <img alt='<?php echo $dintao->title;?> - <?php echo Cfg::setting ('site', 'title');?>' src='<?php echo $dintao->cover->url ('500w');?>' />
          </a>
          <figcaption><?php echo $dintao->title;?> - <?php echo Cfg::setting ('site', 'title');?></figcaption>
        </figure>

        <div>
          <div><?php echo $dintao->mini_content (350);?></div>
          <div>
            <div><?php echo implode (' . ', $dintao->keywords ());?></div>
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
