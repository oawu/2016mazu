<form action='<?php echo base_url (array ('admin', $uri_1));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>名 稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo isset ($posts['name']) ? $posts['name'] : '';?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入名稱!' autofocus />
        </td>
      </tr>

      <tr>
        <th>前台顯示：</th>
        <td>
          <select name='is_on_site'>
      <?php if ($isOnSiteNames = ArticleTag::$isOnSiteNames) {
              foreach ($isOnSiteNames as $key => $name) { ?>
                <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_on_site']) ? $posts['is_on_site'] : ArticleTag::NO_ON_SITE_NAMES) == $key ? ' selected': '';?>><?php echo $name;?></option>
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
