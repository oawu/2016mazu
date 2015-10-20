<article class='ds'>
<?php
  if ($dintaos) {
    foreach ($dintaos as $dintao) { ?>
      <section>
        <a href='<?php echo base_url ('dintao', $method, $dintao->site_content_page_last_uri ());?>' class='i_t'<?php echo ($color = $dintao->cover_color ('rgba', 0.8)) ? ' style="border-color: ' . $color . ';"' : '' ;?>>
          <?php echo img ($dintao->cover->url ('180x130c'));?>
        </a>
        <div class='r'>
          <a href='<?php echo base_url ('dintao', $method, $dintao->site_content_page_last_uri ());?>'><h2><?php echo $dintao->title;?></h2></a>
          <div><?php echo $dintao->mini_content (300);?></div>
          <div><?php echo implode ('.', $dintao->keywords ());?></div>
          <a href='<?php echo base_url ('dintao', $method, $dintao->site_content_page_last_uri ());?>'>更多內容..</a>
        </div>
      </section>
<?php
    }
  } else { ?>
    <div>目前尚未有任何的資料。</div>
<?php
  }?>
</article>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
