<form action='<?php echo base_url ('admin', 'roles', $role, 'menus');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i5'>
    <input type='text' name='id' value='<?php echo @$columns['id'];?>' placeholder='請輸入 ID..' />
    <input type='text' name='text' value='<?php echo @$columns['text'];?>' placeholder='請輸入 文字..' />
    <input type='text' name='href' value='<?php echo @$columns['href'];?>' placeholder='請輸入 網址..' />
    <input type='text' name='class' value='<?php echo @$columns['class'];?>' placeholder='請輸入 類別..' />
    <input type='text' name='method' value='<?php echo @$columns['method'];?>' placeholder='請輸入 方法..' />
  </div>
  <button type='submit'>尋找</button>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($menus) {
        foreach ($menus as $menu) { ?>
          <tr>
            <td data-title='ID' width='80'><?php echo $menu->id;?></td>
            <td data-title='路徑' class='left'><?php echo implode (' > ', column_array ($menu->ancestry (), 'text'));?></td>
            <td data-title='圖示' width='50' class='left'><i class='<?php echo $menu->icon;?>'></i></td>
            <td data-title='類別' width='150'><?php echo $menu->class;?></td>
            <td data-title='方法' width='150'><?php echo $menu->method;?></td>
            <td data-title='子項目數量' width='130'><?php echo anchor (base_url ('admin', 'menus', $menu->id), count ($menu->children));?></td>
            <td data-title='編輯' width='100'>
              <a href='<?php echo base_url ('admin', 'menus', 'edit', $menu->id);?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'menus', 'destroy', $menu->id);?>' class='icon-bin'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_cell', 'pagination', $pagination);?>

