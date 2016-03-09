<form action='<?php echo base_url (array ('admin', $uri_1));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>Type：</th>
        <td>
          <input type='text' name='type' value='<?php echo isset ($posts['type']) ? $posts['type'] : '';?>' placeholder='請輸入Type..' maxlength='50' pattern='.{1,50}' required title='輸入Type!' autofocus />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' />
          <input type='text' name='url' value='<?php echo isset ($posts['url']) ? $posts['url'] : '';?>' placeholder='請輸入封面網址..' title='輸入封面網址!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo isset ($posts['keywords']) ? $posts['keywords'] : '';?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search' data-src='[name="title"], [name="content"]'></div>
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='cke' placeholder='請輸入描述..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-i='0' data-ms='<?php echo json_encode ($posts['sources']);?>'>
          <div class='ma'><button type='button' class='icon-plus'></button></div>
        </td>
      </tr>

      <tr>
        <th>是否啟用：</th>
        <td>
          <select name='is_enabled'>
      <?php if ($isIsEnabledNames = Other::$isIsEnabledNames) {
              foreach ($isIsEnabledNames as $key => $name) { ?>
                <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : Other::NO_ENABLED) == $key ? ' selected': '';?>><?php echo $name;?></option>
        <?php }
            }?>
          </select>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', $uri_1);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
