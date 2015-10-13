<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Frame_cell extends Cell_Controller {

  /* render_cell ('frame_cell', 'wrapper_left', var1, ..); */
  // public function _cache_wrapper_left ($type, $class = null, $method = null) {
  //   $class = $class ? $class : $this->CI->get_class ();
  //   $method = $method ? $method : $this->CI->get_method ();

  //   return array ('time' => 60 * 60, 'key' => $type . '_' . $class . '_' . $method);
  // }
  public function wrapper_left ($type, $class = null, $method = null) {
    $class = $class ? $class : $this->CI->get_class ();
    $method = $method ? $method : $this->CI->get_method ();
    $menus_list = Cfg::setting ('menu', $type);

    return $this->load_view (array (
        'menus_list' => $menus_list,
        'class' => $class,
        'method' => $method
      ));
  }

  /* render_cell ('frame_cell', 'navbar', var1, ..); */
  // public function _cache_nav ($type, $subtitle = '', $back_link = '') {
  //   return array ('time' => 60 * 60, 'key' => $type . '_' . $subtitle);
  // }
  public function navbar ($type, $subtitle = '', $back_link = '') {
    return $this->load_view (array (
        'type' => $type,
        'subtitle' => $subtitle,
        'back_link' => $back_link,
      ));
  }

  /* render_cell ('frame_cell', 'footer', var1, ..); */
  // public function footer ($type) {
  //   return array ('time' => 60 * 60, 'key' => $type);
  // }
  public function footer ($type) {
    return $this->load_view ();
  }

  /* render_cell ('frame_cell', 'tabs', var1, ..); */
  // public function tabs ($type, $tabs = array (), $index = null) {
  //   return array ('time' => 60 * 60, 'key' => $type . '_' . implode ('|', array_keys ($tabs)) . ($index !== null ? '_' . $index : ''));
  // }
  public function tabs ($type, $tabs = array (), $index = null) {

    return $this->load_view (array (
        'tabs' => $tabs,
        'index' => $index
      ));
  }

  /* render_cell ('frame_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}