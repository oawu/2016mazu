<form action='<?php echo base_url ('admin', 'roles');?>' method='get'<?php echo $has_search ? ' class="show"' : '';?>>
  <div class='l i3'>
    <input type='text' name='id' value='<?php echo @$columns['id'];?>' placeholder='請輸入 ID..' />
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 標題..' />
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 標題..' />
  </div>
  <button type='submit'>尋找</button>
  <!-- <a href=''>新增</a> -->
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'search_feature icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'search_feature icon-chevron-right').prev ().removeClass ('show');" class='search_feature icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($roles) {
        foreach ($roles as $role) { ?>
          <tr>
            <td data-title='ID'><?php echo $role->id;?></td>
            <td data-title='名稱' width='200' ><?php echo $role->name;?></td>
            <td data-title='下載' width='200'>
              <a href='<?php echo base_url ('roles', 'edit', $role->id);?>' class='icon-in'></a>
            </td>
          </tr>
          <tr>
            <td data-title='ID'><?php echo $role->id;?></td>
            <td data-title='名稱' width='200' ><?php echo $role->name;?></td>
            <td data-title='下載' width='200'>
              <a href='<?php echo base_url ('roles', 'edit', $role->id);?>' class='icon-in'></a>
            </td>
          </tr>
          <tr>
            <td data-title='ID'><?php echo $role->id;?></td>
            <td data-title='名稱' width='200' ><?php echo $role->name;?></td>
            <td data-title='下載' width='200'>
              <a href='<?php echo base_url ('roles', 'edit', $role->id);?>' class='icon-in'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('site_cell', 'pagination', $pagination);?>

