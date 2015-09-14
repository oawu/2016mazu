<form action='<?php echo base_url (array ('admin', 'users', $user->id, 'set_roles'));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>
      <tr>
        <th>名 稱：</th>
        <td>
          <?php echo $user->name;?>
        </td>
      </tr>
      <tr>
        <th>信 箱：</th>
        <td>
          <?php echo $user->email;?>
        </td>
      </tr>
      <tr>
        <th>上次登入：</th>
        <td class='timeago' data-time='<?php echo $user->logined_at->format ('Y-m-d H:i:s');?>'>
          <?php echo $user->logined_at->format ('Y-m-d H:i:s');?>
        </td>
      </tr>
      <tr>
        <th>註冊時間：</th>
        <td  class='timeago' data-time='<?php echo $user->created_at->format ('Y-m-d H:i:s');?>'>
          <?php echo $user->created_at->format ('Y-m-d H:i:s');?></td>
        </td>
      </tr>

<?php if ($roles) { ?>
        <tr>
          <th>角 色：</th>
          <td>
      <?php foreach ($roles as $role) { ?>
              <label><input type='checkbox' name='role_ids[]' value='<?php echo $role->id;?>'<?php echo $user->user_roles && in_array ($role->id, column_array ($user->user_roles, 'role_id')) ? ' checked' : '';?>/><div><?php echo $role->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php } ?>
      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'users');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
