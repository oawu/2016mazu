<div class='map'>
  <div id='map'></div>
  <div id='like' class="fb-like" data-href="<?php echo base_url ('gps');?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  <a id='location'>我的位置</a>
  <a id='traffic'>交通路況</a>

  <label id='marches' class='n<?php echo count ($marches = March::find ('all', array ('select' => 'id,title', 'conditions' => array ('is_enabled = 1')))) + 1;?>'>
    <span>所有陣頭位置</span>
    <div>
<?php foreach ($marches as $march) { ?>
        <a val='<?php echo $march->id;?>'><?php echo $march->title;?> 目前位置</a>
<?php }?>
      <a class='a'>所有陣頭位置</a>
    </div>
  </label>

  <div id='add_zoom'></div>
  <div id='sub_zoom'></div>
  <div id='zoom'></div>
  
  <div id='menu' class='fi-m'></div>
  <div id='message' class='icon-chat-3'></div>

  <div id='message_panel'>
    <div class='top'>
      <textarea id='msg' placeholder='在想些什麼？'></textarea>
      <button type='button' id='send'>確定送出</button>
    </div>
    <div class='bottom'></div>
  </div>
  <div id='message_cover'></div>

  <div id='tip1'>更多功能！</div>
  <div id='tip2'>即時聊天！</div>
  <div id='tip3'>GPS 準度會因訊號強弱影響，所以僅供參考。</div>
</div>