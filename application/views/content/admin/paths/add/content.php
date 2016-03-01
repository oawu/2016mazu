<form id='fm' action='<?php echo base_url (array ('admin', 'paths'));?>' method='post' enctype='multipart/form-data'>
<?php
  if (isset ($posts['points']) && $posts['points']) {
    foreach ($posts['points'] as $point) { ?>
      <input type='hidden' class='ori_points' data-lat='<?php echo $point['lat'];?>' data-lng='<?php echo $point['lng'];?>'/>
  <?php
    }
  } ?>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>關鍵字：</th>
        <td class='k'>
          <input type='text' name='keywords' value='<?php echo isset ($posts['keywords']) ? $posts['keywords'] : '';?>' placeholder='請輸入關鍵字..' maxlength='200' pattern='.{1,200}' required title='輸入關鍵字!' />
          <div class='icon-search' data-src='[name="title"]'></div>
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
        <th>路 線：</th>
        <td class='map'>
          <i></i><i></i><i></i><i></i>
          <div id='map'></div>
          <div id='length'>0</div>
          <div id='map_menu'><div><div class='add_marker'>新增節點</div></div></div>
          <div id='marker_menu'><div><div class='del'>刪除節點</div></div></div>
          <div id='polyline_menu'><div><div class='add'>插入節點</div></div></div>
          <div id='zoom'></div>
        </td>
      </tr>

      <tr>
        <th>是否啟用：</th>
        <td>
          <select name='is_enabled'>
      <?php if ($isIsEnabledNames = Path::$isIsEnabledNames) {
              foreach ($isIsEnabledNames as $key => $name) { ?>
                <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : Path::NO_ENABLED) == $key ? ' selected': '';?>><?php echo $name;?></option>
        <?php }
            }?>
          </select>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'paths');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
