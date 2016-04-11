<form id='fm' action='<?php echo base_url (array ('admin', $uri_1, $info->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />

<?php
  if ((isset ($posts['latitude']) && ($latitude = $posts['latitude'])) || ($latitude = $info->latitude)) { ?>
    <input type='hidden' class='ori_lat' value='<?php echo $latitude;?>'/>
<?php
  }
  if ((isset ($posts['longitude']) && ($longitude = $posts['longitude'])) || ($longitude = $info->longitude)) { ?>
    <input type='hidden' class='ori_lng' value='<?php echo $longitude;?>'/>
<?php
  }
  if ($points) {
    foreach ($points as $point) { ?>
      <input type='hidden' class='points' data-lat='<?php echo $point['a'];?>' data-lng='<?php echo $point['n'];?>'/>
  <?php
    }
  } ?>

  <table class='table-form'>
    <tbody>


      <tr>
        <th>資 訊：</th>
        <td class='sm' data-i='0' data-msm='<?php echo json_encode ($posts['messages']);?>'>
          <div class='mam'><button type='button' class='icon-plus'></button></div>
        </td>
      </tr>



      <tr>
        <th>地 點：</th>
        <td class='map'>
          <i></i><i></i><i></i><i></i>
          <div id='map' data-icon='<?php echo resource_url ('resource', 'image', 'map', 'mazu.png');?>' data-lat='<?php echo $last->latitude2;?>' data-lng='<?php echo $last->longitude2;?>'></div>
          <div id='zoom'></div>
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
