<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Admin_login_cell extends Cell_Controller {

  /* render_cell ('admin_login_cell', 'loading', var1, ..); */
  // public function loading () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function loading () {
    return $this->load_view ();
  }

  /* render_cell ('admin_login_cell', 'footer', var1, ..); */
  // public function footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->load_view (array (
      ));
  }
}