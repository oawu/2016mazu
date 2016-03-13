<form id='fm' action='<?php echo base_url (array ('admin', $uri_1, $path->id, $uri_2));?>' method='post' enctype='multipart/form-data'>
<?php
  if (isset ($posts['latitude']) && $posts['latitude']) { ?>
    <input type='hidden' class='ori_latitude' value='<?php echo $post['latitude'];?>'/>
<?php
  }
  if (isset ($posts['longitude']) && $posts['longitude']) { ?>
    <input type='hidden' class='ori_longitude' value='<?php echo $post['longitude'];?>'/>
<?php
  }
  if ($path->points) {
    foreach ($path->points as $point) { ?>
      <input type='hidden' class='points' data-lat='<?php echo $point->latitude;?>' data-lng='<?php echo $point->longitude;?>'/>
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
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' />
          <input type='text' name='url' value='<?php echo isset ($posts['url']) ? $posts['url'] : '';?>' placeholder='請輸入封面網址..' title='輸入封面網址!' />
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='pure' placeholder='請輸入描述..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>類 型：</th>
        <td>
          <div class='types'>
        <?php foreach (PathInfo::icon_urls () as $i => $url) { ?>
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
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', $uri_1, $path->id, $uri_2);?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
