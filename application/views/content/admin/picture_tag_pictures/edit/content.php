<form action='<?php echo base_url (array ('admin', 'picture_tags', $tag->id, 'pictures', $picture->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : $picture->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>照 片：</th>
        <td>
          <?php echo img ($picture->name->url (), false, 'class="name"');?>
          <input type='file' name='name' value='' />
          <input type='text' name='url' value='<?php echo $posts['url'] ? $posts['url'] : '';?>' placeholder='請輸入照片網址..' title='輸入照片網址!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo $posts['keywords'] ? $posts['keywords'] : $picture->keywords;?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search'></div>
        </td>
      </tr>
      
      <tr>
        <th>描 述：</th>
        <td>
          <textarea name='description' class='cke' placeholder='請輸入描述..'><?php echo $posts['description'] ? $posts['description'] : $picture->description;?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-ms='<?php echo $posts['sources'] ? json_encode (array_slice ($posts['sources'], 0)) : ($picture->sources ? json_encode (array_map (function ($source) {return array ('title' => $source->title, 'href' => $source->href);}, $picture->sources)): json_encode (array ()));?>'>
          <div class='ma'>
            <button type='button' class='icon-plus'></button>
          </div>
        </td>
      </tr>
      
      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'picture_tags', $tag->id, 'picture');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
