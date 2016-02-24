<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Site_frame_cell extends Cell_Controller {

  /* render_cell ('site_frame_cell', 'header', var1, ..); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header ($subtitle = '', $back_link = '') {
    return $this->load_view (array (
          'subtitle' => $subtitle,
          'back_link' => $back_link
        ));
  }

  /* render_cell ('site_frame_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function wrapper_left ($menus_list, $class, $metohd, $uri = null) {
    $class = $class ? $class : $this->CI->router->fetch_class ();
    $metohd = $metohd ? $metohd : $this->CI->router->fetch_method ();
    
    return $this->load_view (array (
          'menus_list' => $menus_list,
          'c' => $class,
          'm' => $metohd,
          'uri' => $uri,
        ));
  }

  /* render_cell ('site_frame_cell', 'tabs', var1, ..); */
  // public function _cache_tabs () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function tabs ($tabs = array (), $index = null) {
    return $this->load_view (array (
        'tabs' => $tabs,
        'index' => $index
      ));
  }

  /* render_cell ('site_frame_cell', 'footer', var1, ..); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view ();
  }
}