<div class='map'>
  <div id='map' data-icon='<?php echo resource_url ('resource', 'image', 'map', 'mazu.png');?>'></div>
  <div id='like' class="fb-like" data-href="<?php echo base_url ('maps', 'gps');?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
  <div id='length'>100</div>

  <div id='add_zoom'></div>
  <div id='sub_zoom'></div>
  <div id='zoom'></div>

  <div id='btns'>
    <a id='navigation'>規劃路線</a>
    <a id='traffic'>交通路況</a>
    <a id='position'>媽祖位置</a>
    <a id='location'>我的位置</a>
    <label><input type='checkbox' /></label>
  </div>


  <label id='heatmap' class='n5'>
    <span>不顯示用戶分佈</span>
    <div>
      <a>目前用戶分佈</a>
      <a>1 小時前分佈</a>
      <a>2 小時前分佈</a>
      <a>3 小時前分佈</a>
      <a class='a'>不顯示用戶分佈</a>
    </div>
  </label>

  <div id='chat_panel'>
    <div class='top'>
      <textarea id='msg' placeholder='在想些什麼？'></textarea>
      <button type='button' id='send'>確定送出</button>
    </div>
    <div class='bottom'>
      <!-- <span>※ 檢舉</span>
      <span>目前線上 5人</span> -->

      <div>
        <span class='icon-user'></span>
        <span>檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>
      
      <div>
        <span class='icon-user2'></span>
        <span>檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>
      
      <div>
        <span class='icon-user2'></span>
        <span>檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>
      
      <div>
        <span class='icon-user2'></span>
        <span>檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉檢舉</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>

      <div>
        <span class='icon-user'></span>
        <span>adssssssssssssss</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>
      <div>
        <span class='icon-user2'></span>
        <span>adssssssssssssss</span>
        <a>檢舉</a>
        <div>127.0.0.1</div>
        <div>1 分鐘之前</div>
      </div>
    </div>
  </div>
  <div id='chat_cover'></div>

  <div id='direction_panel'><div class='control'><span>請選擇交通工具：</span><div class='r'><input type='radio' id='driving' name='travel_mode' value='DRIVING' checked><span></span><label for='driving'>開車</label></div><div class='r'><input type='radio' id='transit' name='travel_mode' value='TRANSIT'><span></span><label for='transit'>大眾運輸</label></div><div class='r'><input type='radio' id='walking' name='travel_mode' value='WALKING'><span></span><label for='walking'>走路</label></div></div><div class='paths'></div></div>
  <div id='direction_cover'></div>

  <div id='menu' class='fi-m'></div>
  <div id='chat' class='icon-chat-3'></div>
  <div id='tip1'>這有功能選單喔！</div>
  <div id='tip2'>這裡可以聊天喔！</div>
</div>
