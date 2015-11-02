<form action='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'dintaos');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i2 n1'>
    <input type='text' name='title' value='<?php echo @$columns['title'];?>' placeholder='請輸入 標題..' />
    <input type='text' name='keywords' value='<?php echo @$columns['keywords'];?>' placeholder='請輸入 關鍵字..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'dintaos', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($dintaos) {
        foreach ($dintaos as $dintao) { ?>
          <tr>
            <td data-title='標題' width='150'><?php echo $dintao->title;?></td>
            <td data-title='照片' width='50'><?php echo img ($dintao->cover->url ('30x30c', false, 'class="i_30"'));?></td>
            <td data-title='內容' width='' class='left'><?php echo $dintao->mini_description ();?></td>
            <td data-title='關鍵字' width='150' class='left'><?php echo $dintao->mini_keywords ();?></td>
            <td data-title='編輯' width='80'>
              <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'dintaos', $dintao->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'dintaos', $dintao->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
            <td data-title='排序' width='60' class='sort'>
              <a data-id='<?php echo $dintao->id;?>' data-sort='up' class='icon-triangle-up'></a>
              <a data-id='<?php echo $dintao->id;?>' data-sort='down' class='icon-triangle-down'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>
