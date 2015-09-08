<form action='<?php echo base_url ('admin', 'roles');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i2 n'>
    <input type='text' name='id' value='<?php echo @$columns['id'];?>' placeholder='請輸入 ID..' />
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 標題..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'roles', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($roles) {
        foreach ($roles as $role) { ?>
          <tr>
            <td data-title='ID' width='80'><?php echo $role->id;?></td>
            <td data-title='名稱'><?php echo $role->name;?></td>
            <td data-title='選單數量' width='150'><?php echo count ($role->menu_roles);?></td>
            <td data-title='使用者數量' width='150'><?php echo count ($role->user_roles);?></td>
            <td data-title='編輯' width='100'>
              <a href='<?php echo base_url ('', 'roles', 'edit', $role->id);?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('', 'roles', 'destroy', $role->id);?>' class='icon-bin'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_cell', 'pagination', $pagination);?>

