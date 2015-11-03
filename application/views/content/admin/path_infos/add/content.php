<form id='fm' action='<?php echo base_url (array ('admin', 'paths', $path->id, 'infos'));?>' method='post' enctype='multipart/form-data'>

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
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' />
          <input type='text' name='url' value='<?php echo $posts['url'] ? $posts['url'] : '';?>' placeholder='請輸入封面網址..' title='輸入封面網址!' />
        </td>
      </tr>

      <tr>
        <th>描 述：</th>
        <td>
          <textarea name='description' class='autosize pure' placeholder='請輸入描述..' pattern='.{1,}' required title='輸入描述!' ><?php echo $posts['description'] ? $posts['description'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>類 型：</th>
        <td>
          <div class='types'>
        <?php foreach (PathInfo::icon_urls () as $i => $url) { ?>
                <label for='type_<?php echo $i;?>'>
                  <input type='radio' id='type_<?php echo $i;?>' name='type' value='<?php echo $i;?>'<?php echo $posts['type'] ? $posts['type'] == $i : !$i ? ' checked' : '';?>/>
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
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'paths', $path->id, 'infos');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
