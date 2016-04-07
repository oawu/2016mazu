<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Main extends Site_controller {

  public function index () {
    $march19 = '2016-04-25 00:00:00';

    $temp = new DateTime ($march19);
    $day_count = $temp->diff (new DateTime (date ('Y-m-d H:i:s')))->format ('%a');
    $day_count = strtotime ($march19) - strtotime (date ('Y-m-d H:i:s')) < 0 ? 0 - $day_count : $day_count;

    $path = Path::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', 1, Path::IS_ENABLED)));
    $polyline = json_encode (array_map (function ($p) { return array ('a' => $p->latitude, 'n' => $p->longitude);}, $path->points));

    $prev = array ();
    $next = array (
        'url' => base_url ('articles'),
        'title' => '笨港文化'
      );

    $store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', 4, Store::IS_ENABLED)));
    $store_json = json_encode (array (
        'u' => base_url ('stores', $store->id),
        't' => $store->title,
        'c' => $store->mini_content (50),
        'i' => $store->icon_url (),
        'o' => $store->cover->url ('230x115c'),
        'a' => $store->latitude,
        'n' => $store->longitude
      ));

    if ($tags = array_unique (array_merge (array ('北港迎媽祖'), Cfg::setting ('site', 'keywords'))))
      foreach ($tags as $i => $tag)
        if (!$i) $this->add_meta (array ('property' => 'article:section', 'content' => $tag))->add_meta (array ('property' => 'article:tag', 'content' => $tag));
        else $this->add_meta (array ('property' => 'article:tag', 'content' => $tag));
           
    foreach (array ('articles', 'others', 'march19', 'maps/dintao', 'maps/iko', 'dintaos', 'pictures', 'youtubes', 'stores') as $uri)
      $this->add_meta (array ('property' => 'og:see_also', 'content' => base_url ($uri)));

    $title = '網站首頁';
    $desc = '烘爐引炮驚奇火花驚震全場，輪廓描繪傳承力量霓彩妝童延續風華… 不變的開場詞，是的，又一年了！這個慶典對於北港人，就像如候鳥的季節，是一個返鄉的時刻… 在外地的北港囝仔，還記得北港的鞭炮味嗎？還記得小時候期待三月十九到來的期待與喜悅感嗎？這是我們北港人最榮耀的過年，今年要記得回來，再忙都要回來幫媽祖婆逗熱鬧一下吧！';
    $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)
         ->add_js (Cfg::setting ('google', 'client_js_url'), false)
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))

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
        
         ->load_view (array (
            'march19' => $march19,
            'day_count' => $day_count,
            'path' => $path,
            'store' => $store,
            'store_json' => $store_json,
            'polyline' => $polyline,
            'prev' => $prev,
            'next' => $next,
          ), false, 60);
  }
}
