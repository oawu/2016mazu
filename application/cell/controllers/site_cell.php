<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Site_cell extends Cell_Controller {

  /* render_cell ('site_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function wrapper_left () {
    $class = $this->CI->get_class ();
    $method = $this->CI->get_method ();

    $item_lists = array (
      '主選單' => array (
          array ('name' => '首頁', 'href' => base_url (), 'icon' => 'icon-home', 'target' => '_self', 'visible' => true, 'active' => ($class == 'main') && ($method == 'index')),
          array ('name' => '今日最新', 'href' => base_url ('avs', 'today'), 'icon' => 'icon-fire', 'target' => '_self', 'visible' => true, 'active' => ($class == 'avs') && ($method == 'today')),
          array ('name' => '所有影片', 'href' => base_url ('avs'), 'icon' => 'icon-list2', 'target' => '_self', 'visible' => true, 'active' => ($class == 'avs') && ($method == 'index')),
          array ('name' => '所有標籤', 'href' => base_url ('tags'), 'icon' => 'icon-tag', 'target' => '_self', 'visible' => true, 'active' => ($class == 'tags') && ($method == 'index')),
        ),
      '其他功能'=> array (
          array ('name' => '排程紀錄', 'href' => base_url ('logs'), 'icon' => 'icon-clipboard', 'target' => '_self', 'visible' => true, 'active' => ($class == 'logs') && ($method == 'index')),
        ),
    );
    return $this->load_view (array (
        'item_lists' => $item_lists
      ));
  }

  /* render_cell ('site_cell', 'nav', var1, ..); */
  // public function nav () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function nav () {
    return $this->load_view (array (
      ));
  }

  /* render_cell ('site_cell', 'loading', var1, ..); */
  // public function loading () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function loading () {
    return $this->load_view ();
  }

  /* render_cell ('site_cell', 'footer', var1, ..); */
  // public function footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view (array (
      ));
  }

  /* render_cell ('site_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}