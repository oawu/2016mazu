<form action='<?php echo base_url (array ('admin', 'roles', 'create'));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>
      <tr>
        <th>角色名稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo $posts['name'] ? $posts['name'] : '';?>' placeholder='請輸入角色名稱..' maxlength='200' pattern='.{1,200}' required title='輸入角色名稱!' />
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'roles');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
