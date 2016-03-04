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
        array ('id' => 1, 'title' => '十九上午'),
        array ('id' => 2, 'title' => '十九下午'),
        array ('id' => 3, 'title' => '十九晚上'),
        array ('id' => 4, 'title' => '二十上午'),
        array ('id' => 5, 'title' => '二十下午'),
        array ('id' => 6, 'title' => '二十晚上')
      );

    $this->iko_tabs = array (
        array ('id' => 7,  'title' => '十九下午'),
        array ('id' => 8,  'title' => '十九晚間'),
        array ('id' => 9,  'title' => '二十下午'),
        array ('id' => 10, 'title' => '二十晚間'),
        array ('id' => 11, 'title' => '廿一晚間'),
        array ('id' => 12, 'title' => '廿二晚間'),
        array ('id' => 13, 'title' => '廿三晚間'),
      );

    $this->add_js (Cfg::setting ('google', 'client_js_url'), false);
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

    $this->set_tab_index ($index)
         ->set_subtitle ('三月' . $this->dintao_tabs[$index]['title'] . ' 陣頭路關')
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
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

    $this->set_tab_index ($index)
         ->set_subtitle ('三月' . $this->iko_tabs[$index]['title'] . ' 藝閣路關')
         ->add_js (resource_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'id', 'value' => $path->id))
         ->set_method ('dintao')
         ->load_view (array (
            'path' => $path,
            'polyline' => $polyline,
            'infos' => $infos,
          ));
  }
}
