<form id='fm' action='<?php echo base_url (array ('admin', 'paths', $path->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
<?php
  if ($points = $posts['points'] ? $posts['points'] : $path->points) {
    foreach ($points as $point) { ?>
      <input type='hidden' class='ori_points' data-lat='<?php echo is_array ($point) ? $point['lat'] : $point->latitude;?>' data-lng='<?php echo is_array ($point) ? $point['lng'] : $point->longitude;?>'/>
  <?php
    }
  } ?>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo $posts['title'] ? $posts['title'] : $path->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' />
        </td>
      </tr>

      <tr>
        <th>路 線：</th>
        <td class='map'>
          <i></i><i></i><i></i><i></i>
          <div id='map'></div>
          <div id='length'>0</div>
          <div id='map_menu'><div><div class='add_marker'>新增節點</div></div></div>
          <div id='marker_menu'><div><div class='del'>刪除節點</div></div></div>
          <div id='polyline_menu'><div><div class='add'>插入節點</div></div></div>
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
