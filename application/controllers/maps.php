<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Maps extends Site_controller {
  private $dintao_tabs = array ();

  public function __construct () {
    parent::__construct ();

    $this->dintao_tabs = array (
        // array ('id' => 1, 'title' => '十九上午'),
        array ('id' => 1, 'title' => '十九下午'),
        array ('id' => 2, 'title' => '十九晚上'),
        // array ('id' => 4, 'title' => '二十上午'),
        array ('id' => 3, 'title' => '二十下午'),
        array ('id' => 4, 'title' => '二十晚上')
      );

    $this->iko_tabs = array (
        array ('id' => 5, 'title' => '十九下午'),
        array ('id' => 6, 'title' => '十九晚間'),
        array ('id' => 7, 'title' => '二十下午'),
        array ('id' => 8, 'title' => '二十晚間'),
        array ('id' => 9, 'title' => '廿一晚間'),
        array ('id' => 10, 'title' => '廿二晚間'),
        array ('id' => 11, 'title' => '廿三晚間'),
      );

    $this->add_js (Cfg::setting ('google', 'client_js_url'), false);
  }
  public function gps () {
    
    $this->set_subtitle ('三月十九，神轎定位')
         ->add_css (base_url ('application', 'views', 'content', 'site', 'maps', 'gps', 'a.css'))
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'url3', 'value' => base_url ('api', 'march_messages')))
         ->add_hidden (array ('id' => 'url4', 'value' => base_url ('api', 'march_messages', 'report')))
         ->load_view (array (
          ));
  }
  public function dintao ($index = 0) {
    if (!(isset ($this->dintao_tabs[$index]) && ($path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', $this->dintao_tabs[$index]['id'], Path::IS_ENABLED))))))
      return redirect_message (array ('march19'), array (
          '_flash_message' => '找不到該筆資料。'
        ));

    foreach ($this->dintao_tabs as $i => $tab)
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
          'url' => base_url ('march19', 'iko'),
          'title' => '藝閣路關'
        );
    else if (isset ($this->dintao_tabs[$index - 1]))
      $prev = array (
          'url' => base_url ($this->get_class (), 'dintao', $index - 1),
          'title' => '三月' . $this->dintao_tabs[$index - 1]['title'] . ' 陣頭地圖'
        );
    else 
      $prev = null;

    if (isset ($this->dintao_tabs[$index + 1]))
      $next = array (
          'url' => base_url ('maps', 'dintao', $index + 1),
          'title' => '三月' . $this->dintao_tabs[$index + 1]['title'] . ' 陣頭地圖'
        );
    else if ($index + 1 == count ($this->dintao_tabs))
      $next = array (
          'url' => base_url ('maps', 'iko'),
          'title' => '三月' . $this->iko_tabs[0]['title'] . ' 藝閣地圖'
        );
    else
      $next = null;

    
    $title = '三月' . $this->dintao_tabs[$index]['title'] . ' 陣頭地圖';
    $desc = '出廟➜中山路➜民生路➜益安路➜信義路➜捷發街➜光明路➜益安路➜東興街➜中秋路➜東勢街➜東華巷➜彌陀寺➜前中央市場後➜厚生路➜媽祖廟後➜國宮旅社前➜仁和路➜三連街➜公館街➜大同路➜博愛路➜大復戲院前➜賜福街➜義民路➜復興街➜文化路➜民有路..';
    
    if ($tags = array_unique (array_merge (array ($title), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', '', 'march19', 'march19/dintao', 'march19/kio', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $this->set_tab_index ($index)
         ->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)

         ->add_meta (array ('name' => 'keywords', 'content' => implode (',', $tags)))
         ->add_meta (array ('name' => 'description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:title', 'content' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:description', 'content' => $desc))
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'larger2.jpg'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')))
        
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
            'prev' => $prev,
            'next' => $next,
          ));
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

    $this->set_tab_index ($index)
         ->set_subtitle ('三月' . $this->iko_tabs[$index]['title'] . ' 藝閣地圖')
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->set_method ('dintao')
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
            'prev' => $prev,
            'next' => $next,
          ));
  }
}
