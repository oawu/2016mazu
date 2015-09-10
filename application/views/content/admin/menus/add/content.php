<form action='<?php echo base_url (array ('admin', 'menus', 'create', $parent_menu ? $parent_menu->id : 0));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>
      <tr>
        <th>文 字：</th>
        <td>
          <input type='text' name='text' value='<?php echo $posts['text'] ? $posts['text'] : '';?>' placeholder='請輸入文字名稱..' maxlength='200' pattern='.{1,200}' required title='輸入文字名稱!' />
        </td>
      </tr>
      <tr>
        <th>網 址：</th>
        <td>
          <input type='text' name='href' value='<?php echo $posts['href'] ? $posts['href'] : '';?>' placeholder='請輸入網址名稱..' maxlength='200' />
        </td>
      </tr>
      <tr>
        <th>Target：</th>
        <td>
          <select name='target'>
      <?php foreach (Menu::$targets as $target => $text) { ?>
              <option value='<?php echo $target;?>'<?php echo $posts['target'] && $posts['target'] == $target ? ' selected' : '';?>><?php echo $text;?></option>
      <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <th>圖 示：</th>
        <td>
          <input type='text' name='icon' value='<?php echo $posts['icon'] ? $posts['icon'] : '';?>' placeholder='請輸入圖示名稱..'/>
        </td>
      </tr>
      <tr>
        <th>類 別：</th>
        <td>
          <input type='text' name='class' value='<?php echo $posts['class'] ? $posts['class'] : '';?>' placeholder='請輸入類別名稱..'/>
        </td>
      </tr>
      <tr>
        <th>方 法：</th>
        <td>
          <input type='text' name='method' value='<?php echo $posts['method'] ? $posts['method'] : '';?>' placeholder='請輸入方法名稱..'/>
        </td>
      </tr>
<?php if ($roles) { ?>
        <tr>
          <th>角 色：</th>
          <td>
      <?php foreach ($roles as $role) { ?>
              <label><input type='checkbox' name='role_ids[]' value='<?php echo $role->id;?>' /><div><?php echo $role->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php } ?>
      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'menus', $parent_menu ? $parent_menu->id : 0);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
