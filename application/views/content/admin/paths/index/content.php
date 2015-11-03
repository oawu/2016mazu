<form action='<?php echo base_url ('admin', 'paths');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i1 n1'>
    <input type='text' name='title' value='<?php echo @$columns['title'];?>' placeholder='請輸入 標題..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'paths', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($paths) {
        foreach ($paths as $path) { ?>
          <tr>
            <td data-title='截圖' width='80'><?php echo (string)$path->image ? img ($path->image->url ('30x30c'), false, 'class="i_30"') : '-';?></td>
            <td data-title='標題' width=''><?php echo $path->title;?></td>
            <td data-title='長度' width='150'><?php echo number_format ($path->length, 2);?>(m)</td>
            <td data-title='資訊' width='150'><?php echo implode ('', array_map (function ($info) use ($path) { return anchor (base_url ('admin', 'paths', $path->id, 'infos'), $info->title, 'class="info"'); }, $path->infos));?></td>
            <td data-title='編輯' width='120'>
              <a href='<?php echo base_url ('admin', 'paths', $path->id, 'infos');?>' class='icon-pin_drop'></a>
              <a href='<?php echo base_url ('admin', 'paths', $path->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'paths', $path->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

