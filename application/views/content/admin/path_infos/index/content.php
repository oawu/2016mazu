<form action='<?php echo base_url ('admin', 'paths', $path->id, 'infos');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i2 n1'>
    <input type='text' name='title' value='<?php echo @$columns['title'];?>' placeholder='請輸入 標題..' />
    <input type='text' name='description' value='<?php echo @$columns['description'];?>' placeholder='請輸入 描述..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'paths', $path->id, 'infos', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($infos) {
        foreach ($infos as $info) { ?>
          <tr>
            <td data-title='標題' width=''><?php echo $info->title;?></td>
            <td data-title='截圖' width='80'><?php echo (string)$info->image ? img ($info->image->url ('30x30c'), false, 'class="i_30"') : '-';?></td>
            <td data-title='封面' width='80'><?php echo (string)$info->cover ? img ($info->cover->url ('30x30c'), false, 'class="i_30"') : '-';?></td>
            <td data-title='編輯' width='80'>
              <a href='<?php echo base_url ('admin', 'paths', $path->id, 'infos', $info->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'paths', $path->id, 'infos', $info->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

