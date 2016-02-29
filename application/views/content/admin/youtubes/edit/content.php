<form action='<?php echo base_url (array ('admin', $uri_1, $youtube->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : $youtube->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>影片來源：</th>
        <td>
          <?php echo (string)$youtube->cover ? img ($youtube->cover->url ('100x100c'), false, 'class="cover"') : '';?>
          <input type='text' name='url' value='<?php echo isset ($posts['url']) ? $posts['url'] : $youtube->url;?>' placeholder='請輸入影片網址..' title='輸入影片網址!' />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo isset ($posts['keywords']) ? $posts['keywords'] : $youtube->keywords;?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search' data-src='[name="title"], [name="content"]'></div>
        </td>
      </tr>
      
<?php if ($tags) { ?>
        <tr>
          <th>標 籤：</th>
          <td>
      <?php $tag_ids = isset ($posts['tag_ids']) ? $posts['tag_ids'] : column_array ($youtube->mappings, 'youtube_tag_id');
            foreach ($tags as $tag) { ?>
              <label><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo $tag_ids && in_array ($tag->id, $tag_ids) ? ' checked' : '';?>/><div><?php echo $tag->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php }?>

      <tr>
        <th>描 述：</th>
        <td>
          <textarea name='content' class='cke' placeholder='請輸入描述..'><?php echo isset ($posts['content']) ? $posts['content'] : $youtube->content;?></textarea>
        </td>
      </tr>

      <tr>
        <th>參 考：</th>
        <td class='s' data-i='0' data-ms='<?php echo json_encode ($posts['sources']);?>'>
          <div class='ma'>
            <button type='button' class='icon-plus'></button>
          </div>
        </td>
      </tr>

      <tr>
        <th>是否啟用：</th>
        <td>
          <select name='is_enabled'>
      <?php if ($isIsEnabledNames = Youtube::$isIsEnabledNames) {
              foreach ($isIsEnabledNames as $key => $name) { ?>
                <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : $youtube->is_enabled) == $key ? ' selected': '';?>><?php echo $name;?></option>
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
