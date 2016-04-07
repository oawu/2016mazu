<form action='<?php echo base_url ('admin', 'users');?>' method='get' class="search<?php echo $has_search = array_filter (column_array ($columns, 'value')) ? ' show' : '';?>">
<?php 
  if ($columns) { ?>
    <div class='l i<?php echo count ($columns);?>'>
<?php foreach ($columns as $column) {
        if (isset ($column['select']) && $column['select']) { ?>
          <select name='<?php echo $column['key'];?>'>
            <option value=''>請選擇 <?php echo $column['title'];?>..</option>
      <?php foreach ($column['select'] as $option) { ?>
              <option value='<?php echo $option['value'];?>'<?php echo $option['value'] === $column['value'] ? ' selected' : '';?>><?php echo $option['text'];?></option>
      <?php } ?>
          </select>
  <?php } else { ?>
          <input type='text' name='<?php echo $column['key'];?>' value='<?php echo $column['value'];?>' placeholder='請輸入 <?php echo $column['title'];?>..' />
<?php   }
      }?>
    </div>
    <button type='submit'>尋找</button>
<?php 
  } else { ?>
    <div class='l i0'></div>
<?php 
  }?>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($users) {
        foreach ($users as $user) { ?>
          <tr>
            <td data-title='ID' width='80'><?php echo $user->id;?></td>
            <td data-title='頭像' width='50'><?php echo img ($user->avatar (30, 30), false, 'class="i_30"');?></td>
            <td data-title='名稱'><?php echo $user->name;?></td>
            <td data-title='信箱' width='200'><?php echo $user->email;?></td>
            <td data-title='角色' width='100'><?php echo $user->roles ? implode ('<br/>', $user->role_names ()) : '-';?></td>
            <td data-title='臉書網址' width='100'><?php echo $user->facebook_url ? make_click_enable_link ($user->facebook_url, 10, pathinfo ($user->facebook_url, PATHINFO_BASENAME)) : '-';?></td>
            <td data-title='登入次數' width='80'><?php echo $user->login_count;?>次</td>
            <td data-title='上次登入' width='90' class='timeago' data-time='<?php echo $user->logined_at->format ('Y-m-d H:i:s');?>'><?php echo $user->logined_at->format ('Y-m-d H:i:s');?></td>
            <td data-title='註冊時間' width='90' class='timeago' data-time='<?php echo $user->created_at->format ('Y-m-d H:i:s');?>'><?php echo $user->created_at->format ('Y-m-d H:i:s');?></td>
            <td data-title='修改' width='50'>
              <a href='<?php echo base_url ('admin', 'users', $user->id, 'edit');?>' class='icon-pencil2'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

