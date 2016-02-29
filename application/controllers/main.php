<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Main extends Site_controller {

  public function spec () {
    $this->set_frame_path ('frame', 'pure')
         ->load_view ();
  }
  public function index () {
    $this->add_tab ('陣頭列表', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 1))
         ->add_tab ('xxxx', array ('href' => base_url ('admin', $this->get_class ()), 'index' => 2))
         ->set_tab_index (1)
         ->load_view ();
  }
}
