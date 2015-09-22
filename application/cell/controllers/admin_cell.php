<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Admin_cell extends Cell_Controller {

  // public function _cache_wrapper_left ($class = null, $method = null) {
  //   $class = $class ? $class : $this->CI->get_class ();
  //   $method = $method ? $method : $this->CI->get_method ();

  //   return array ('time' => 60 * 60, 'key' => $class . '_' . $method);
  // }
  public function wrapper_left ($class = null, $method = null) {
    $class = $class ? $class : $this->CI->get_class ();
    $method = $method ? $method : $this->CI->get_method ();

    return $this->load_view (array (
        'class' => $class,
        'method' => $method
      ));
  }

  /* render_cell ('admin_cell', 'nav', var1, ..); */
  // public function nav () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function nav ($subtitle) {
    return $this->load_view (array (
      'subtitle' => $subtitle
      ));
  }

  /* render_cell ('admin_cell', 'footer', var1, ..); */
  // public function footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view (array (
      ));
  }

  /* render_cell ('admin_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}