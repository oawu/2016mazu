<form action='<?php echo base_url ('admin', 'roles', $role, 'users');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i3'>
    <input type='text' name='id' value='<?php echo @$columns['id'];?>' placeholder='請輸入 ID..' />
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 名稱..' />
    <input type='text' name='email' value='<?php echo @$columns['email'];?>' placeholder='請輸入 信箱..' />
  </div>
  <button type='submit'>尋找</button>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($users) {
        foreach ($users as $user) { ?>
          <tr>
            <td data-title='ID' width='80'><?php echo $user->id;?></td>
            <td data-title='名稱'><?php echo $user->name;?></td>
            <td data-title='信箱' width='250'><?php echo $user->email;?></td>
            <td data-title='上次登入' width='150' class='timeago' data-time='<?php echo $user->logined_at->format ('Y-m-d H:i:s');?>'><?php echo $user->logined_at->format ('Y-m-d H:i:s');?></td>
            <td data-title='新增時間' width='200' ><?php echo $user->created_at->format ('Y-m-d H:i:s');?></td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

