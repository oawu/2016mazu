<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Maps extends Site_controller {
  private $dintao_tabs = array ();
  private $iko_tabs = array ();

  public function __construct () {
    parent::__construct ();

    $this->dintao_tabs = array (
        // array ('id' => 1, 'title' => '十九上午', 'desc' => '2016年，三月十九上午陣頭遶境路線圖(地圖版)，出廟➜中山路➜民生路➜向西大橋西引道➜北港大橋➜笨南港➜回程➜笨南港水仙宮➜北港大橋➜大橋東引道➜民生路➜休息'),
        array ('id' => 1, 'title' => '十九下午', 'desc' => '2016年，三月十九下午陣頭遶境路線圖(地圖版)，出廟➜中山路➜民生路➜益安路➜信義路➜捷發街➜光明路➜益安路➜東興街➜中秋路➜東勢街➜東華巷➜彌陀寺➜前中央市場後➜厚生路➜媽祖廟後➜國宮旅社前➜仁和路➜三連街➜公館街➜大同路➜博愛路➜大復戲院前➜賜福街➜義民路➜復興街➜文化路➜民有路➜公園路➜民族路➜文昌路➜民有路➜華勝路➜民治路➜文昌路➜民有路➜華興街➜民治路➜吉祥路➜民有路➜公園路➜文星路➜吉祥路➜民享路➜文昌路➜文明路➜右轉吉祥路➜左轉文仁路➜左轉文昌路➜文明路➜文明路155巷➜仁愛路➜民享路➜文昌路➜民治路➜華勝路➜勝利路➜大同路➜文化路➜公民路➜義民路➜中正路➜電信局前➜媽祖廟西邊➜民主路➜土銀前➜郵局前➜中正路➜左轉民權路➜民主路➜左轉文化路➜右轉文化路27巷➜右轉西勢街➜慈德寺前➜光明路➜義民路➜信義路52巷➜博愛路➜信義路➜中山路➜光明路➜博愛路➜中華路➜中山路➜入廟'),
        array ('id' => 2, 'title' => '十九晚上', 'desc' => '2016年，三月十九晚上陣頭遶境路線圖(地圖版)，出廟➜中山路➜民生路➜博愛路➜光明路➜南陽國小前➜文化路➜西勢街➜旌義街➜義民廟前➜民榮街➜褒新街➜福泰大飯店➜中正路➜花旗銀行前➜郵局前➜民眾服務站前➜民權路➜中正路➜新民路➜大同路➜義民路➜公民路➜博愛路➜民主路➜媽祖廟西邊➜仁和路➜公民路➜仁德街➜大同路➜益安路➜彌陀寺前➜中秋路➜東榮街➜東陽巷➜水源街➜民生路➜中正路➜入廟'),
        // array ('id' => 4, 'title' => '二十上午', 'desc' => '2016年，三月二十上午陣頭遶境路線圖(地圖版)，出廟➜中山路➜光明路➜義民路➜公民路➜新民路➜大同路➜民樂路➜萬有紙廠前➜新街竹圍仔➜新街北壇➜回程➜新街碧水寺➜公園路➜文仁路158巷➜文仁路➜華勝路➜大同路➜光復路➜光復四路➜光復三路➜光復二路➜光復一路➜光復路➜華南路➜民生路➜陸橋西引道➜陸橋東引道➜民生路➜休息'),
        array ('id' => 3, 'title' => '二十下午', 'desc' => '2016年，三月二十下午陣頭遶境路線圖(地圖版)，出廟➜中山路➜民生路➜義民路➜光明路➜博愛路➜代天宮前➜義民路192巷➜義民路➜文化路➜太平路➜文仁路➜公園路➜民政路➜北辰路➜民治路➜公園路➜民族路➜吉祥路➜民有路➜文昌路➜民治路➜公園路➜民享路➜華勝路➜民政路➜公園路➜文星路➜華勝路➜文明路➜大同路516巷出➜長安街➜協和街➜長治街➜介壽街➜文仁路➜大同路➜右轉舊太子宮邊➜懷恩街➜文仁路➜民樂路➜文星路➜仁愛路➜民政路➜華勝路➜民治路➜民樂路➜民享路➜大同路➜義民路➜中正路➜花旗銀行前➜郵局前➜文化路➜光明路➜義民路➜旌義街➜義民廟前➜博愛路➜中華路➜共和街➜安和街➜中山路➜光明路➜德為街➜信義路➜興南街➜民生路➜益安路➜中華路➜中山路➜入廟'),
        array ('id' => 4, 'title' => '二十晚上', 'desc' => '2016年，三月二十晚上陣頭遶境路線圖(地圖版)，出廟➜中山路➜民生路➜水源街➜東興街➜益安路➜公民路➜仁和路➜三連街➜公館街➜仁德街➜舊台南中小企銀前➜博愛路➜中興街➜媽祖廟東邊➜媽祖廟前媽祖廟西邊➜中正路➜文化路➜嘉義客運前➜公民路➜義民路➜大同路➜博愛路➜代天宮前➜復興街➜文化路➜郵局前➜文化路➜文化路27巷➜文明巷➜慈德堂前➜光明路➜南陽國小前➜文化路➜西勢街➜義民路➜民主路➜博愛路➜光明路➜新興街➜信義路➜義民路➜民生路➜中山路➜入廟'),
      );
    $this->iko_tabs = array (
        array ('id' => 5, 'title' => '十九下午', 'desc' => '2016年，三月十九下午藝閣遶境路線圖(地圖版)，廟前起馬➜中山路➜東引道➜陸橋➜義民路➜民主路➜文化路➜大同路➜華勝路➜民享路➜公園路➜民治路➜北辰路➜文化路➜太平路➜文仁路➜北辰路➜公園路➜文仁路➜民樂路➜文明路➜仁愛路➜民享路➜華勝路➜華南路➜休息'),
        array ('id' => 6, 'title' => '十九晚間', 'desc' => '2016年，三月十九晚間藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜東引道➜陸橋➜義民路➜大同路➜文昌路➜民族路➜文化路➜民有路➜華勝路➜民治路➜民樂路➜民政路➜公園路➜文星路➜華勝路➜大同路➜文化路➜民主路➜義民路➜陸橋➜東引道➜中山路➜休息'),
        array ('id' => 7, 'title' => '二十下午', 'desc' => '2016年，三月二十下午藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜東引道➜陸橋➜義民路➜大同路➜華勝路➜民享路➜民樂路➜穎寧街➜華勝路➜北辰派出所前➜新街巡天宮前迴轉南向➜北辰派出所前➜華勝路➜文仁路➜吉祥路➜文明路➜公園路➜文星路➜吉祥路➜民有路➜華勝路➜華南路➜休息'),
        array ('id' => 8, 'title' => '二十晚間', 'desc' => '2016年，三月二十晚間藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜民生路➜義民路➜民主路➜文化路➜中正路➜仁和路➜大同路➜文昌路➜文明路➜華勝路➜民政路➜公園路➜文化路➜民主路➜義民路➜陸橋➜東引道➜中山路➜休息'),
        array ('id' => 9, 'title' => '廿一晚間', 'desc' => '2016年，三月廿一晚間藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜民生路➜益安路➜大同路➜義民路➜中正路➜廟後➜廟東➜廟前➜中山路➜光明路➜文化路➜圓環➜休息'),
        array ('id' => 10, 'title' => '廿二晚間', 'desc' => '2016年，三月廿二晚間藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜集合後開始評審➜民生路➜益安路➜公民路➜文化路➜民主路➜義民路➜民生路➜中山路➜廟前定點評審➜民主路➜圓環➜休息'),
        array ('id' => 11, 'title' => '廿三晚間', 'desc' => '2016年，三月廿三晚間藝閣遶境路線圖(地圖版)，廟前出發➜中山路➜民生路➜義民路➜大同路➜新民路➜中正路➜仁和路➜益安路➜民生路➜中山路➜廟前頒獎及落馬儀式➜民主路➜圓環➜圓滿結束'),
      );

    $this->add_js (Cfg::setting ('google', 'client_js_url'), false);
  }
  public function gps () {
    $m = March::find_by_id (2);
    $p = $m->paths2 (5);
    $p = array_map(function ($p) {
      return array (
          'latitude' => $p['a'],
          'longitude' => $p['n'],
          'time' => date('Y-m-d H:i:s')
        );
    }, $p['p']);
    return $this->output_json ($p);


    // $this->set_subtitle ('三月十九，神轎定位')
    //      ->set_frame_path ('frame', 'pure')
    //      ->load_view (array (
    //         'p' => json_encode ($p['p'])
    //       ));
  }
  public function dintao ($index = 0) {
    $march19 = '2016-04-25 00:00:00';

    $temp = new DateTime ($march19);
    $day_count = $temp->diff (new DateTime (date ('Y-m-d H:i:s')))->format ('%a');
    $day_count = strtotime ($march19) - strtotime (date ('Y-m-d H:i:s')) < 0 ? 0 - $day_count : $day_count;

    if (false && date ('Y-m-d H:i:s') >= $march19) {
      $title = '三月十九陣頭遶境 GPS 定位';
      $desc = '2016年，農曆三月十九，陣頭GPS定位系統，一起分享，讓大家更快的找到北港各個陣頭的位置，以及追蹤目前北港媽祖的所在地，一起準備恭迎聖駕！希望大家一起幫忙把這個網站分享給更多的北港人，或者分享給更多想認識北港的朋友吧！';

      if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
             
      foreach (array ('articles', 'others', '', 'march19', 'march19/dintao', 'march19/kio', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

      $this->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
           ->add_meta (array ('name' => 'description', 'content' => $desc))
           ->add_meta (array ('property' => 'og:title', 'content' => '農曆' . $title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => $desc))
           ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'large.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'));

      redirect (base_url ('gps'));
    } else {
      $title = '三月' . $this->dintao_tabs[$index]['title'] . ' 陣頭遶境 Google Maps 路線地圖';
      $desc = $this->dintao_tabs[$index]['desc'];

      if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
        foreach ($tags as $i => $tag)
          if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
          else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
             
      foreach (array ('articles', 'others', '', 'march19', 'march19/dintao', 'march19/kio', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
        $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

      $this->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
           ->add_meta (array ('name' => 'description', 'content' => $desc))
           ->add_meta (array ('property' => 'og:title', 'content' => '農曆' . $title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:description', 'content' => $desc))
           ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'dintao', 'larger.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
           ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
           ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
           ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'));
    }

    if (!(isset ($this->dintao_tabs[$index]) && ($path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->dintao_tabs[$index]['id'], Path::IS_ENABLED))))))
      return redirect_message (array ('march19'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    foreach ($this->dintao_tabs as $i => $tab)
      $this->add_tab ($tab['title'], array ('href' => base_url ($this->get_class (), $this->get_method (), $i), 'index' => $i));

    $polyline = json_encode (array_map (function ($p) { return array ('a' => $p->latitude, 'n' => $p->longitude);}, $path->points));
    $infos = json_encode (array_map (function ($i) { return array ('t' => $i->title,'c' => $i->content,'i' => $i->icon_url (),'o' => $i->cover->url ('230x115c'),'a' => $i->latitude,'n' => $i->longitude); }, $path->infos));

    if ($index == 0)
      $prev = array ('url' => base_url ('march19', 'iko'), 'title' => '藝閣路關');
    else if (isset ($this->dintao_tabs[$index - 1]))
      $prev = array ('url' => base_url ($this->get_class (), 'dintao', $index - 1), 'title' => '三月' . $this->dintao_tabs[$index - 1]['title'] . ' 陣頭地圖');
    else 
      $prev = null;

    if (isset ($this->dintao_tabs[$index + 1]))
      $next = array ('url' => base_url ('maps', 'dintao', $index + 1), 'title' => '三月' . $this->dintao_tabs[$index + 1]['title'] . ' 陣頭地圖');
    else if ($index + 1 == count ($this->dintao_tabs))
      $next = array ('url' => base_url ('maps', 'iko'), 'title' => '三月' . $this->iko_tabs[0]['title'] . ' 藝閣地圖');
    else
      $next = null;

    
    $change = array ();
    if ($index == 1)
      $change = array (array ('t' => '更改路線', 'p' => array (array (23.564366974005416, 120.30494257807732),array (23.5642796956743, 120.30540391802788),array (23.565421685028067, 120.30562117695808),array (23.565512650383916, 120.30515044927597))));
    if ($index == 2)
      $change = array (array ('t' => '更改路線1', 'p' => array (array (23.565516338167285, 120.30514776706696), array (23.565420455766063, 120.30561983585358))),
                       array ('t' => '更改路線2', 'p' => array (array (23.573073618025234, 120.29972702264786), array (23.572953157313293, 120.29891967773438), array (23.57257456578495, 120.29879361391068), array (23.572176305986194, 120.3000408411026))));
    if ($index == 3)
      $change = array (array ('t' => '更改路線', 'p' => array (array (23.56548191885183, 120.30531406402588), array (23.56542291429005, 120.3056252002716), array (23.564267402946943, 120.30541598796844), array (23.564373120364568, 120.30493050813675))));

    $this->set_tab_index ($index)
         ->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)
         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')))
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->set_method ('index')
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
            'prev' => $prev,
            'next' => $next,
            'change' => $change,
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
  public function iko ($index = 0) {
    if (!(isset ($this->iko_tabs[$index]) && ($path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->iko_tabs[$index]['id'], Path::IS_ENABLED))))))
      return redirect_message (array ('march19'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    foreach ($this->iko_tabs as $i => $tab)
      $this->add_tab ($tab['title'], array ('href' => base_url ($this->get_class (), $this->get_method (), $i), 'index' => $i));

    $polyline = json_encode (array_map (function ($p) { return array ('a' => $p->latitude, 'n' => $p->longitude);}, $path->points));
    $infos = json_encode (array_map (function ($i) {
      return array (
        't' => $i->title,
        'c' => $i->content,
        'i' => $i->icon_url (),
        'o' => $i->cover->url ('230x115c'),
        'a' => $i->latitude,
        'n' => $i->longitude);
    }, $path->infos));

    if ($index == 0)
      $prev = array (
          'url' => base_url ('maps', 'dintao', $i = count ($this->dintao_tabs) - 1),
          'title' => '三月' . $this->dintao_tabs[$i]['title'] . ' 陣頭地圖'
        );
    else if (isset ($this->iko_tabs[$index - 1]))
      $prev = array (
          'url' => base_url ($this->get_class (), 'iko', $index - 1),
          'title' => '三月' . $this->iko_tabs[$index - 1]['title'] . ' 藝閣地圖'
        );
    else 
      $prev = null;

    if (isset ($this->iko_tabs[$index + 1]))
      $next = array (
          'url' => base_url ('maps', 'iko', $index + 1),
          'title' => '三月' . $this->iko_tabs[$index + 1]['title'] . ' 藝閣地圖'
        );
    else if ($index + 1 == count ($this->iko_tabs))
      $next = array (
          'url' => base_url ('dintaos'),
          'title' => '所有陣頭',
        );
    else
      $next = null;

    $title = '三月' . $this->iko_tabs[$index]['title'] . ' 藝閣遶境 Google Maps 路線地圖';
    $desc = $this->iko_tabs[$index]['desc'];

    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'march19', 'march19/dintao', 'march19/kio', 'maps/dintao', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));



    $this->set_tab_index ($index)
         ->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)

         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
         ->add_meta (array ('name' => 'description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:title', 'content' => '農曆' . $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $desc))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'iko', 'larger.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'story', 'content' => $img = resource_url ('resource', 'image', 'og', 'iko', 'story.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'story', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'story', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'story', 'content' => '600'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'small', 'content' => $img = resource_url ('resource', 'image', 'og', 'iko', 'small.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'small', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'small', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'small', 'content' => '315'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'mini', 'content' => $img = resource_url ('resource', 'image', 'og', 'iko', 'mini.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'mini', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'mini', 'content' => '200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'mini', 'content' => '200'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'non-stoty', 'content' => $img = resource_url ('resource', 'image', 'og', 'iko', 'non-stoty.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'non-stoty', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'non-stoty', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'non-stoty', 'content' => '314'))

         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')))
          
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->set_method ('index')
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
            'prev' => $prev,
            'next' => $next,
            'change' => array ()
          ), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
}
