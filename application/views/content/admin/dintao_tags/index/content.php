<form action='<?php echo base_url ('admin', 'dintao_tags');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i2 n1'>
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 名稱..' />
    <input type='text' name='keywords' value='<?php echo @$columns['keywords'];?>' placeholder='請輸入 關鍵字..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'dintao_tags', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($tags) {
        foreach ($tags as $tag) { ?>
          <tr>
            <td data-title='名稱' width='150'><?php echo $tag->name;?></td>
            <td data-title='封面' width='50'><?php echo (string)$tag->cover ? img ($tag->cover->url ('30x30c'), false, 'class="i_30"') : '-';?></td>
            <td data-title='關鍵字' width='' class='left'><?php echo $tag->mini_keywords ();?></td>
            <td data-title='照片數量' width='80'><?php echo count ($tag->mappings);?></td>
            <td data-title='編輯' width='120'>
              <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'dintaos');?>' class='icon-images'></a>
              <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'dintao_tags', $tag->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
            <td data-title='排序' width='60' class='sort'>
              <a data-id='<?php echo $tag->id;?>' data-sort='up' class='icon-triangle-up'></a>
              <a data-id='<?php echo $tag->id;?>' data-sort='down' class='icon-triangle-down'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

