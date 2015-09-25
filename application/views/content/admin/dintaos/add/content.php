<form action='<?php echo base_url (array ('admin', 'dintaos', 'create', $tab_index));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>名 稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo $posts['name'] ? $posts['name'] : '';?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入名稱!' />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' accept='image/gif, image/jpeg, image/jpg, image/png' required title='請選擇圖片(gif、jpg、png)檔案!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keyword' value='<?php echo $posts['keyword'] ? $posts['keyword'] : '';?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='ckeditor' placeholder='請輸入內容..'><?php echo $posts['content'] ? $posts['content'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-ms='<?php echo $posts['sources'] ? json_encode (array_slice ($posts['sources'], 0)) : json_encode (array ());?>'>
          <div class='ma'>
            <button type='button' class='icon-plus'></button>
          </div>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'dintaos', $tab_index);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
