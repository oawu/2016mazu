<div class='map'>
  <div id='map' data-icon='<?php echo resource_url ('resource', 'image', 'map', 'mazu.png');?>' data-polyline='<?php echo $polyline;?>' data-infos='<?php echo $infos;?>'></div>
  <div id='like' class="fb-like" data-href="<?php echo current_url ();?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  <div id='length'><?php echo $path->length ();?></div>
  <div id='zoom'></div>
<?php
  if ($tabs) { ?>
    <label id='other'>
      <input type='checkbox'>
      <div class='icon-triangle-down2 n<?php echo count ($tabs);?>'>
  <?php foreach ($tabs as $text => $tab) { ?>
          <a<?php echo isset ($tab['href']) ? " href='" . $tab['href'] . "'" : '';?><?php echo ($tab_index !== null) && isset ($tab['index']) && ($tab['index'] == $tab_index) ? " class='a'": '';?>><?php echo $text;?></a>
  <?php } ?>
      </div>
      <span></span>
    </label>
<?php
  } ?>
</div>
<div id='menu' class='fi-m'></div>