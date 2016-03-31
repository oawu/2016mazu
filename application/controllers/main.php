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

    $store = Store::find ('one', array ('conditions' => array ('id = ? AND destroy_user_id IS NULL AND is_enabled = ?', 1, Store::IS_ENABLED)));
    $store = json_encode (array (
        'u' => base_url ('stores', $store->id),
        't' => $store->title,
        'c' => $store->mini_content (50),
        'i' => $store->icon_url (),
        'o' => $store->cover->url ('230x115c'),
        'a' => $store->latitude,
        'n' => $store->longitude
      ));

    $this->set_title ('網站首頁')
         ->set_subtitle ('網站首頁')
         ->add_css (resource_url ('resource', 'css', 'OA-mobileScrollView', 'OA-mobileScrollView.css'))
         ->add_js (Cfg::setting ('google', 'client_js_url'), false)
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->load_view (array (
            'march19' => $march19,
            'day_count' => $day_count,
            'path' => $path,
            'store' => $store,
            'polyline' => $polyline,
            'prev' => $prev,
            'next' => $next,
          ), false, 60);
  }
}
