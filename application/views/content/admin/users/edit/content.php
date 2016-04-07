<form action='<?php echo base_url (array ('admin', 'users', $user->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>名 稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo isset ($posts['name']) ? $posts['name'] : $user->name;?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入名稱!' autofocus />
        </td>
      </tr>
      
      <tr>
        <th>電子郵件：</th>
        <td>
          <input type='text' name='email' value='<?php echo isset ($posts['email']) ? $posts['email'] : $user->email;?>' placeholder='請輸入電子郵件..' maxlength='200' pattern='.{1,200}' required title='輸入電子郵件!' />
        </td>
      </tr>
      
      <tr>
        <th>臉書網址：</th>
        <td>
          <input type='text' name='facebook_url' value='<?php echo isset ($posts['facebook_url']) ? $posts['facebook_url'] : $user->facebook_url;?>' placeholder='請輸入電子郵件..' maxlength='200' pattern='.{1,200}' required title='輸入電子郵件!' />
        </td>
      </tr>

<?php if ($roles = Cfg::setting ('role', 'role_names')) { ?>
        <tr>
          <th>角 色：</th>
          <td>
      <?php $last_roles = isset ($posts['roles']) ? $posts['roles'] : column_array ($user->roles, 'name');
            foreach ($roles as $key => $name) { ?>
              <label><input type='checkbox' name='roles[]' value='<?php echo $key;?>'<?php echo $last_roles && in_array ($key, $last_roles) ? ' checked' : '';?>/><div><?php echo $name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php }?>

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
