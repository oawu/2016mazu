<article>
  <section class='r'>
    <h2>
<?php if ($day_count > 0) { ?>
        <span>歲次丙申年 農曆</span>三月十九<span>日</span>倒數 <?php echo $day_count;?> 天！
<?php } else if ($day_count > -3) { ?>
        <u>今天就是</u> <span>歲次丙申年 農曆</span>三月十九<span>日</span>！
<?php } else { ?>
        <span>歲次丙申年 農曆</span>三月十九<span>日</span>已完滿落幕！
<?php }?>
      <div class="fb-like" data-href="<?php echo base_url ('march19', 'dintao');?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></h2>
    
    <figure>
      <a href=''>
        <img alt="農曆三月熱鬧北港 - <?php echo Cfg::setting ('site', 'title');?>" src="<?php echo resource_url ('resource', 'image', 'static-article', '05.jpg');?>" />
      </a>
      <figcaption>農曆三月熱鬧北港</figcaption>
    </figure>

    <div id='c'>2016年 農曆三月十九日 <span data-day='<?php echo $day_count;?>' data-end='<?php echo strtotime (date ($march19));?>'></span>！</div>
    <p>烘爐引炮 驚奇火花 驚震全場，輪廓描繪傳承力量 霓彩妝童延續風華，三聲起馬炮 三鼓三哨聲的先鋒中壇開路啟程，兩聲哨鼓的北港黃袍勇士也在砲火花中吞雲吐霧聞炮起舞，四小將鏘鏘響 門一開 青紅將軍開路展威風！</p>
    <p>不變的，還是一樣的開場詞，是的，又一年了！這個慶典對於北港人，就像如候鳥的季節，是一個返鄉的時刻！每年十九前一晚，小鎮內車子就漸漸的多了，辦桌的廚棚也滿在街道上，這是一個屬於北港囝仔的春節、北港人的過年！</p>
    <p>十幾年過去了 不曾改變的習慣還依然繼續！不曾冷卻的期待也依然澎湃！在外地的北港囝仔，還記得北港的鞭炮味嗎？還記得小時候期待三月十九到來的期待與喜悅感嗎？這是我們北港人最榮耀的過年，今年要記得回來，再忙都要回來幫媽祖婆逗熱鬧一下吧！</p>
  </section>

  <section>
    <h2>三月十九遶境路線圖</h2>

    <a class='map' title='<?php echo $path->title;?>'>
      <div id='map' data-icon='<?php echo resource_url ('resource', 'image', 'map', 'mazu.png');?>' data-polyline='<?php echo $polyline;?>'></div>
    </a>

    <p>這是我們利用 Google Maps 提供的 JavaScript API，製作的會移動的三月十九繞境路關地圖和提供多項北港文化的說明頁面，然而為了跟上手機時代，就將網頁技術上提升、導入 RWD 技術，讓更多使用者可以用手機瀏覽！</p>
    <p>在我們地圖專業上更有三月十九、二十不同時段的陣頭、藝閣遶境地圖，網站上更提供了 RWD 技術，讓手機、平板、電腦瀏覽網站時，都可以有最適合網頁大小的瀏覽方式！</p>
    <p>如果可以的話，希望大家一起幫忙把這個網站分享給更多的北港人，或者分享給更多想認識北港的朋友吧！</p>
  </section>

  <section class='r'>
    <h2>鄉土文化</h2>

    <figure>
      <a href=''>
        <img alt="觀音殿的龍柱 - <?php echo Cfg::setting ('site', 'title');?>" src="<?php echo resource_url ('resource', 'image', 'static-article', '04.png');?>" />
      </a>
      <figcaption>觀音殿的龍柱</figcaption>
    </figure>

    <p>家鄉的鄉土文化當然不僅限於朝天宮的文物，舉凡北港圓環的顏思齊紀念碑、三級古蹟義民廟、知名景點甕牆、美食小吃，這些都是北港的在地文化、特色。其實來到這古鎮，可以看到的不止是香客人潮、也不止是香紙鞭炮，如果細心品嘗這些人文藝術，其實可以看到先民開墾台灣的痕跡。</p>
    <p>而身為雲林家鄉一份子的我們會收集更多的北港、雲林..等等多的地文化、文獻，用不同的、新技術的方式來呈現這美麗的鄉土藝術。雖然時代進步的同時會有很多過去先民的故事漸漸被遺忘，甚至多年前的網路資源也漸漸的逝去，但期許未來在這裡可以整理與提供出豐富的在地文化，讓更多人可以一起欣賞與討論。</p>
    <p>當然不免的紀錄文章多少會有爭議等問題，也很歡迎各位一起討論與指教，未來我們更會規劃留言等功能，甚至讓大家一起參與、編輯這個紀錄在地文化的網站！</p>
  </section>

  <section>
    <figure>
      <a href=''>
        <img alt="2013年 北港迎媽祖 - <?php echo Cfg::setting ('site', 'title');?>" src="<?php echo resource_url ('resource', 'image', 'static-article', '01.jpg');?>" />
      </a>
      <figcaption>2013年 北港迎媽祖</figcaption>
    </figure>

    <h2>多媒體影音</h2>
    <p>圖文、照片、影音，都是現代科技呈現知識的最好表現，我們利用 AWS S3、Youtube 等技術，可以存取大量的數位多媒體資源，讓更的舊照片、影音紀錄片得以保存。</p>
    <p>當然多數照片與影片都也會引用自其他網路資源、媒介、平台，但我們都會加上資源來源的鏈結，希望各位在欣賞這些多媒體影音的同時可以標注分享來源。</p>
  </section>

  <section class='r'>
    <h2>美食地圖</h2>

    <a class='food' title='<?php echo $store->title;?>'>
      <div id='food' data-store='<?php echo $store;?>'></div>
    </a>

    <p>古鎮，當然的會想到美食！北港當然少不了在地美味的小吃，百年小鎮就代表著這塊土地上孕育著多樣佳餚，也相對的經過多少年的歷練，所以很多很多北港的美味料理都會記錄在這地圖上。</p>
    <p>來到北港遊玩，若是懶得上網找零散的網路文章介紹的話，那倒不如來北港的美食地圖吧！我們紀錄的不只是美食小吃，就連住宿旅遊、名勝古蹟，也會一同整理起來呦。</p>
    <p>這個美食地圖，在未來我們更會規劃出各式各樣的旅遊套餐路線！讓來自各地的背包客可以用最在地人的角度去欣賞這美麗的小鎮！</p>
  </section>

</article>


<?php
  if ($prev || $next) { ?>
    <div class='np a'>
<?php if ($prev) { ?>
        <figure class='p'>
          <a href='<?php echo $prev['url'];?>'></a>
          <figcaption><a href='<?php echo $prev['url'];?>'><?php echo $prev['title'];?></a></figcaption>
        </figure>
      <?php
      }
      if ($next) {?>
        <figure class='n'>
          <a href='<?php echo $next['url'];?>'></a>
          <figcaption><a href='<?php echo $next['url'];?>'><?php echo $next['title'];?></a></figcaption>
        </figure>
      <?php
      }?>
    </div>
<?php 
  }