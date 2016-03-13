<form id='fm' action='<?php echo base_url (array ('admin', $uri_1));?>' method='post' enctype='multipart/form-data'>
<?php
  if (isset ($posts['latitude']) && $posts['latitude']) { ?>
    <input type='hidden' class='ori_latitude' value='<?php echo $post['latitude'];?>'/>
<?php
  }
  if (isset ($posts['longitude']) && $posts['longitude']) { ?>
    <input type='hidden' class='ori_longitude' value='<?php echo $post['longitude'];?>'/>
<?php
  }?>

  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
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

<?php if ($tags) { ?>
        <tr>
          <th>標 籤：</th>
          <td>
      <?php foreach ($tags as $tag) { ?>
              <label><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo isset ($posts['tag_ids']) && $posts['tag_ids'] && in_array ($tag->id, $posts['tag_ids']) ? ' checked' : '';?>/><div><?php echo $tag->name;?></div></label>
      <?php } ?>
          </td>
        </tr>
<?php }?>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='cke' placeholder='請輸入描述..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>類 型：</th>
        <td>
          <div class='types'>
        <?php foreach (Store::icon_urls () as $i => $url) { ?>
                <label for='type_<?php echo $i;?>'>
                  <input type='radio' id='type_<?php echo $i;?>' name='type' value='<?php echo $i;?>'<?php echo isset ($posts['type']) ? $posts['type'] == $i : !$i ? ' checked' : '';?>/>
                  <img src='<?php echo $url;?>' />
                </label>  
        <?php } ?>
          </div>
        </td>
      </tr>

      <tr>
        <th>地 點：</th>
        <td class='map'>
          <i></i><i></i><i></i><i></i>
          <div id='map'></div>
          <div id='zoom'></div>
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
      <?php if ($isIsEnabledNames = Store::$isIsEnabledNames) {
              foreach ($isIsEnabledNames as $key => $name) { ?>
                <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : Store::NO_ENABLED) == $key ? ' selected': '';?>><?php echo $name;?></option>
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
