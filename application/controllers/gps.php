<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Gps extends Site_controller {

  // public function path () {
  //   $setting = GpsSetting::find_by_id (1);

  //   if (!(($id = GpsSetting::find_by_id (1)->path_id) && ($path = Path::find_by_id ($id))))
  //     return $this->output_json (array ('m' => array ()));

  //   $points = array_map (function ($point) {
  //     return array (
  //         'a' => $point->lat,
  //         'n' => $point->lng,
  //       );
  //   }, $path->mini_points ('', false));
    
  //   return $this->output_json (array ('m' => $points), ENVIRONMENT == 'production' ? 60 : 0);
  // }
  public function index () {

    
    $title = '三月十九陣頭遶境 GPS 定位';
    $desc = '2016年，農曆三月十九，陣頭GPS定位系統，一起分享，讓大家更快的找到北港各個陣頭的位置，以及追蹤目前北港媽祖的所在地，一起準備恭迎聖駕！';

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
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'larger', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'larger.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'larger', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'larger', 'content' => '1200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'larger', 'content' => '630'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'story', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'story.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'story', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'story', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'story', 'content' => '600'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'small', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'small.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'small', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'small', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'small', 'content' => '315'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'mini', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'mini.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'mini', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'mini', 'content' => '200'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'mini', 'content' => '200'))
         
         ->add_meta (array ('property' => 'og:image', 'tag' => 'non-stoty', 'content' => $img = resource_url ('resource', 'image', 'og', 'gps', 'non-stoty.png'), 'alt' => $title . ' - ' . Cfg::setting ('site', 'title')))
         ->add_meta (array ('property' => 'og:image:type', 'tag' => 'non-stoty', 'content' => 'image/' . pathinfo ($img, PATHINFO_EXTENSION)))
         ->add_meta (array ('property' => 'og:image:width', 'tag' => 'non-stoty', 'content' => '600'))
         ->add_meta (array ('property' => 'og:image:height', 'tag' => 'non-stoty', 'content' => '314'))
         
         ->add_meta (array ('property' => 'article:modified_time', 'content' => date ('c')))
         ->add_meta (array ('property' => 'article:published_time', 'content' => date ('c')));
        
    $this->set_title ($title . ' - ' . Cfg::setting ('site', 'title'))
         ->set_subtitle ($title)
         ->add_css (base_url ('application', 'views', 'content', 'site', 'maps', 'gps', 'a.css'))
         ->add_js (Cfg::setting ('google', 'client_js_url'), false)
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => '_path_id', 'value' => GpsSetting::find_by_id (1)->path_id))
         ->add_hidden (array ('id' => '_url_set_location', 'value' => base_url ('api', 'march_users')))
         ->add_hidden (array ('id' => '_url_report', 'value' => base_url ('api', 'march_messages', 'report')))
         ->add_hidden (array ('id' => '_url_send_message', 'value' => base_url ('api', 'march_messages')))
         ->load_view (array (), false, ENVIRONMENT == 'production' ? 60 * 3 : 0);
  }
}
