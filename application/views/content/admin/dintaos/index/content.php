<form action='<?php echo base_url ('admin', 'dintaos', $tab_index);?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i3 n1'>
    <input type='text' name='id' value='<?php echo @$columns['id'];?>' placeholder='請輸入 ID..' />
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 名稱..' />
    <input type='text' name='content' value='<?php echo @$columns['content'];?>' placeholder='請輸入 內容..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'dintaos', 'add', $tab_index);?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($dintaos) {
        foreach ($dintaos as $dintao) { ?>
          <tr>
            <td data-title='ID' width='80'><?php echo $dintao->id;?></td>
            <td data-title='編輯' width='120'>
              <a href='<?php echo base_url ('admin', 'dintaos', 'add', $dintao->id);?>' class='icon-plus'></a>
              <a href='<?php echo base_url ('admin', 'dintaos', 'edit', $dintao->id);?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'dintaos', 'destroy', $dintao->id);?>' class='icon-bin'></a>
            </td>
            <td data-title='排序' width='70' class='sort'>
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

