
  <table class='table-list-rwd'>
    <tbody>
<?php if ($roles) {
        foreach ($roles as $key => $role) { ?>
          <tr>
            <td data-title='Key' width='120'><?php echo $key;?></td>
            <td data-title='名稱'><?php echo $role['name'];?></td>
            <td data-title='選單數量' width='120'><?php echo anchor (base_url ('admin', 'roles', $key, 'menus'), $role['menus_count']);?></td>
            <td data-title='使用者數量' width='120'><?php echo anchor (base_url ('admin', 'roles', $key, 'users'), $role['users_count']);?></td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>