<form action='<?php echo base_url (array ('admin', 'picture_tags', 'update', $tag->id));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>名 稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo $posts['name'] ? $posts['name'] : $tag->name;?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入名稱!' />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <?php echo img ($tag->cover->url (), false, 'class="cover"');?>
          <input type='file' name='cover' value='' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo $posts['keywords'] ? $posts['keywords'] : $tag->keywords;?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'picture_tags', $tab_index);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
